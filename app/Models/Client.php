<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Notifications\ClientAccountCreated;

class Client extends Model
{
    use Notifiable;
    protected $fillable = [
        'name', 'phone', 'email', 'birth_date', 'notes',
        'user_id', 'loyalty_points', 'special_discount', 'fidelity_card_number', 'no_show_count', 'last_reactivation_sent_at'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'special_discount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saved(function ($client) {
            if (!$client->user_id) {
                // Check if user already exists by email or phone
                $user = null;
                if ($client->email) {
                    $user = User::where('email', $client->email)->first();
                }
                
                if (!$user && $client->phone) {
                    $user = User::where('phone', $client->phone)->first();
                }
                
                if (!$user && $client->email) {
                    $password = Str::random(8);
                    $user = User::create([
                        'name' => $client->name,
                        'email' => $client->email,
                        'phone' => $client->phone,
                        'password' => Hash::make($password),
                        'role' => 'client',
                    ]);
                    
                    // Notificăm clientul cu parola generată
                    $user->notify(new ClientAccountCreated($password));
                }
                
                if ($user) {
                    // Legăm contul de user de fișa clientului
                    $client->user_id = $user->id;
                    $client->saveQuietly();
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ClientTag::class, 'client_tag_pivot', 'client_id', 'tag_id');
    }

    public function vouchers()
    {
        return $this->hasMany(ClientVoucher::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ClientSubscription::class);
    }

    public static function syncOrphanBookings(User $user)
    {
        $client = $user->client;
        
        $orphanClient = self::whereNull('user_id')
            ->where(function($query) use ($user) {
                if ($user->email) {
                    $query->where('email', $user->email);
                }
                if ($user->phone) {
                    $query->orWhere('phone', $user->phone);
                }
            })
            ->first();

        if ($orphanClient) {
            if ($client) {
                // Merge: Move bookings from orphan to the main client record
                foreach ($orphanClient->bookings as $booking) {
                    $booking->update(['client_id' => $client->id]);
                }
                $orphanClient->delete();
            } else {
                // Just link it
                $orphanClient->update(['user_id' => $user->id]);
            }
        }
    }
}
