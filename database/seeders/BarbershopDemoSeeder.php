<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\WebsiteSetting;
use App\Models\Page;

class BarbershopDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Service Categories
        $categories = [
            ['name' => 'Haircuts', 'icon' => 'scissors'],
            ['name' => 'Beard', 'icon' => 'user'],
            ['name' => 'Packages', 'icon' => 'box'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['name']] = \App\Models\ServiceCategory::updateOrCreate(['name' => $cat['name']], $cat);
        }

        // 1. Barbershop Services
        $services = [
            [
                'name' => 'Tuns Clasic',
                'description' => 'Tuns realizat din foarfece și mașină, finisat cu atenție la detalii.',
                'duration_minutes' => 45,
                'price' => 60.00,
                'category_id' => $categoryModels['Haircuts']->id,
            ],
            [
                'name' => 'Tuns Modern / Skinfade',
                'description' => 'Tuns modern cu degrade de la zero, stilizare și consiliere.',
                'duration_minutes' => 60,
                'price' => 80.00,
                'category_id' => $categoryModels['Haircuts']->id,
            ],
            [
                'name' => 'Aranjat Barbă',
                'description' => 'Contur, scurtat și hidratare cu uleiuri profesionale.',
                'duration_minutes' => 30,
                'price' => 40.00,
                'category_id' => $categoryModels['Beard']->id,
            ],
            [
                'name' => 'Pachet Royal (Tuns + Barbă)',
                'description' => 'Experiența completă: tuns, aranjat barbă și masaj capilar.',
                'duration_minutes' => 90,
                'price' => 110.00,
                'category_id' => $categoryModels['Packages']->id,
            ],
            [
                'name' => 'Bărbierit Tradițional (Hot Towel)',
                'description' => 'Bărbierit cu briciul, prosoape calde și masaj facial.',
                'duration_minutes' => 45,
                'price' => 70.00,
                'category_id' => $categoryModels['Beard']->id,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }

        // 2. Website Settings
        WebsiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'business_name' => 'Elite Barber Studio',
                'primary_color' => '#1a1a1a', 
                'secondary_color' => '#d4af37', 
                'hero_title' => 'Arta Bărbieritului Tradițional',
                'hero_subtitle' => 'Redescoperă stilul masculin într-o atmosferă premium, unde detaliile fac diferența.',
                'hero_button_text' => 'Rezervă un Loc',
                'about_title' => 'Povestea Noastră',
                'about_text' => 'Elite Barber Studio nu este doar o frizerie, este un refugiu pentru bărbatul modern care apreciază calitatea. Cu o echipă de experți pasionați, ne dedicăm fiecărui client pentru a oferi nu doar un tuns, ci o experiență de neuitat.',
                'address' => 'Strada Victoriei nr. 10, București',
                'phone' => '+40 722 000 000',
                'email' => 'contact@elitebarber.ro',
                'seo_title' => 'Elite Barber Studio - Cele mai bune servicii de frizerie din București',
                'seo_description' => 'Tuns clasic, skinfade și bărbierit tradițional cu prosoape calde. Programează-te acum la Elite Barber Studio.',
                'show_services_section' => true,
                'show_about_section' => true,
                'show_contact_section' => true,
                'instagram_url' => 'https://instagram.com',
                'facebook_url' => 'https://facebook.com',
                'tiktok_url' => 'https://tiktok.com',
            ]
        );

        // 3. Sample Pages
        $pages = [
            [
                'title' => 'Termeni și Condiții',
                'slug' => 'termeni-si-conditii',
                'content' => '<h2>Termeni și Condiții</h2><p>Vă rugăm să citiți cu atenție termenii noștri de utilizare...</p>',
                'status' => 'published',
                'show_in_footer' => true,
            ],
            [
                'title' => 'Politica de Confidențialitate',
                'slug' => 'politica-confidentialitate',
                'content' => '<h2>Politica de Confidențialitate</h2><p>Respectăm datele dumneavoastră personale...</p>',
                'status' => 'published',
                'show_in_footer' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
