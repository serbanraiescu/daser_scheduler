<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ClientTag;
use App\Models\Setting;
use App\Models\Voucher;
use App\Models\ClientVoucher;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query()->with('tags');

        if ($request->has('q')) {
            $q = $request->input('q');
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        }

        $clients = $query->latest()->paginate(20);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'birth_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'loyalty_points' => 'nullable|integer',
            'special_discount' => 'nullable|numeric|min:0|max:100',
            'fidelity_card_number' => 'nullable|string|unique:clients,fidelity_card_number',
        ]);

        $client = Client::create($validated);

        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];
            foreach ($tagNames as $name) {
                if (empty($name)) continue;
                $tag = ClientTag::firstOrCreate(['name' => $name]);
                $tagIds[] = $tag->id;
            }
            $client->tags()->sync($tagIds);
        }

        return redirect()->route('admin.clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        $bookings = $client->bookings()
            ->with(['service', 'employee'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        $giftVouchers = \App\Models\GiftVoucher::where('client_id', $client->id)
            ->orWhere('buyer_email', $client->email)
            ->latest()
            ->get();
            
        return view('admin.clients.show', compact('client', 'bookings', 'giftVouchers'));
    }

    public function edit(Client $client)
    {
        $tagsString = $client->tags->pluck('name')->implode(', ');
        return view('admin.clients.edit', compact('client', 'tagsString'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'birth_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'tags' => 'nullable|string',
            'loyalty_points' => 'nullable|integer',
            'special_discount' => 'nullable|numeric|min:0|max:100',
            'fidelity_card_number' => 'nullable|string|unique:clients,fidelity_card_number,'.$client->id,
        ]);

        $client->update($validated);

        if (isset($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];
            foreach ($tagNames as $name) {
                if (empty($name)) continue;
                $tag = ClientTag::firstOrCreate(['name' => $name]);
                $tagIds[] = $tag->id;
            }
            $client->tags()->sync($tagIds);
        }

        // Fidelity Reward Logic
        $threshold = (int) Setting::getValue('fidelity_points_required', 7);
        $vouchersIssued = 0;

        while ($client->loyalty_points >= $threshold) {
            $client->loyalty_points -= $threshold;
            
            $voucher = Voucher::create([
                'code' => 'GRATIS-' . strtoupper(Str::random(6)),
                'type' => 'percentage',
                'value' => 100,
                'max_uses' => 1,
                'active' => true,
            ]);

            ClientVoucher::create([
                'client_id' => $client->id,
                'voucher_id' => $voucher->id,
            ]);

            $vouchersIssued++;
        }

        if ($vouchersIssued > 0) {
            $client->save();
            return redirect()->route('admin.clients.index')->with('success', "Client actualizat cu succes. Vouchere recompensă emise: {$vouchersIssued}.");
        }

        return redirect()->route('admin.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); 

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) continue;

            $client = Client::create([
                'name' => $row[0] ?? '',
                'phone' => $row[1] ?? '',
                'email' => $row[2] ?? null,
                'notes' => $row[3] ?? null,
            ]);

            if (isset($row[4]) && !empty($row[4])) {
                $tagNames = array_map('trim', explode('|', $row[4]));
                $tagIds = [];
                foreach ($tagNames as $name) {
                    $tag = ClientTag::firstOrCreate(['name' => $name]);
                    $tagIds[] = $tag->id;
                }
                $client->tags()->sync($tagIds);
            }
        }

        fclose($handle);

        return redirect()->route('admin.clients.index')->with('success', 'Clients imported successfully.');
    }
}
