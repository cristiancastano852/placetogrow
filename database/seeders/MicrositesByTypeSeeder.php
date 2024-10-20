<?php

namespace Database\Seeders;

use App\Constants\InvoiceStatus;
use App\Constants\MicrositesTypes;
use App\Constants\PaymentStatus;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsites;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MicrositesByTypeSeeder extends Seeder
{
    public function run(): void
    {
        $micrositesData = [
            ['name' => 'Netflix', 'category_name' => 'Entretenimiento', 'user_id' => 2, 'site_type' => 'Subscripciones', 'logo' => 'https://i.pinimg.com/736x/1b/54/ef/1b54efef3720f6ac39647fc420d4a6f9.jpg'],
            ['name' => 'Vaca vías 4G Antoquia', 'category_name' => 'Humanitario', 'user_id' => 2, 'site_type' => 'Donaciones', 'logo' => 'https://tecnologicocoredi.edu.co/wp-content/uploads/2022/06/gobernacion-de-antioquia-logo-1.png'],
            ['name' => 'Spotify', 'category_name' => 'Música', 'user_id' => 2, 'site_type' => 'Subscripciones', 'logo' => 'https://as2.ftcdn.net/v2/jpg/05/62/75/35/1000_F_562753507_4ABUyPLOeujgVjityAfeZUCWz2s1u0TF.jpg'],
            ['name' => 'Claro', 'category_name' => 'Tecnología', 'user_id' => 2, 'site_type' => 'Facturas', 'logo' => 'https://www.tecnogus.com.co/wp-content/uploads/2021/11/ClARO.png'],
            ['name' => 'Amazon Prime', 'category_name' => 'Entretenimiento', 'user_id' => 2, 'site_type' => 'Subscripciones', 'logo' => 'https://d1gntthpnlgab3.cloudfront.net/rails/active_storage/blobs/proxy/eyJfcmFpbHMiOnsibWVzc2FnZSI6IkJBaHBBa05vIiwiZXhwIjpudWxsLCJwdXIiOiJibG9iX2lkIn19--9e13e6b978c833c7d7f8ab952602e99c0f2b2728/unnamed.png'],
            ['name' => 'Nike', 'category_name' => 'Deportes', 'user_id' => 2, 'site_type' => 'Facturas', 'logo' => 'https://www.liderlogo.es/wp-content/uploads/2022/12/pasted-image-0.png'],
            ['name' => 'IKEA', 'category_name' => 'Hogar', 'user_id' => 2, 'site_type' => 'Facturas', 'logo' => 'https://logos-marcas.com/wp-content/uploads/2020/04/IKEA-Logo.png'],
        ];

        foreach ($micrositesData as $data) {
            $category = Category::firstOrCreate(
                ['name' => $data['category_name']],
            );

            $microsite = Microsites::factory()->create([
                'name' => $data['name'],
                'category_id' => $category->id,
                'user_id' => $data['user_id'] ?? 2,
                'site_type' => $data['site_type'] ?? 'Donaciones',
                'logo' => $data['logo'] ?? '',
            ]);

            if ($microsite->site_type === MicrositesTypes::Subscripciones->name) {
                $planBasico = Plan::factory()->create([
                    'microsite_id' => $microsite->id,
                    'name' => 'Básico',
                    'price' => 1000,
                    'billing_frequency' => 1,
                ]);

                $planEstandar = Plan::factory()->create([
                    'microsite_id' => $microsite->id,
                    'name' => 'Estándar',
                    'price' => 2000,
                    'billing_frequency' => 1,
                ]);

                $planPremium = Plan::factory()->create([
                    'microsite_id' => $microsite->id,
                    'name' => 'Premium',
                    'price' => 3000,
                    'billing_frequency' => 1,
                ]);

                $selectedPlan = fake()->randomElement([$planBasico, $planEstandar, $planPremium]);

                Subscription::factory()->create([
                    'microsite_id' => $microsite->id,
                    'plan_id' => $selectedPlan->id,
                    'user_id' => 3,
                    'next_billing_date' => Carbon::now()->addMonth(),
                ]);
            } elseif ($microsite->site_type === MicrositesTypes::Facturas->name) {
                for ($i = 0; $i < 20; $i++) {
                    $createdDate = Carbon::now()->subMonths(1);
                    $expirationDate = Carbon::now()->addDays(rand(1, 7));
                    $status = fake()->randomElement([InvoiceStatus::PAID->name, InvoiceStatus::PENDING->name]);
                    $invoice = Invoice::factory()->create([
                        'microsite_id' => $microsite->id,
                        'expiration_date' => $expirationDate,
                        'status' => $status,
                        'created_at' => $createdDate,
                        'email' => 'guest@microsites.com',
                    ]);
                    if ($status === InvoiceStatus::PAID->name) {
                        Payment::factory()->create([
                            'status' => PaymentStatus::APPROVED->name,
                            'microsite_id' => $microsite->id,
                            'invoice_id' => $invoice->id,
                            'created_at' => $expirationDate,
                            'user_id' => 3,
                        ]);
                    }
                }
                for ($i = 0; $i < 30; $i++) {
                    $createdDate = Carbon::now()->subMonths(rand(1, 4))->addDays(rand(0, 30));
                    $expirationDate = $createdDate->copy()->addDays(rand(1, 30));
                    if ($expirationDate->isPast()) {
                        $status = InvoiceStatus::EXPIRED->name;
                    } else {
                        $status = InvoiceStatus::PENDING->name;
                    }
                    $invoice = Invoice::factory()->create([
                        'email' => 'guest@microsites.com',
                        'microsite_id' => $microsite->id,
                        'expiration_date' => $expirationDate,
                        'status' => $status,
                        'created_at' => $createdDate,
                    ]);
                }
            } elseif ($microsite->site_type === MicrositesTypes::Donaciones->name) {
                for ($i = 0; $i < 30; $i++) {
                    $createdDate = Carbon::now()->subMonths(1)->addDays(rand(0, 30));
                    $status = fake()->randomElement([PaymentStatus::APPROVED, PaymentStatus::REJECTED->name]);
                    Payment::factory()->create([
                        'microsite_id' => $microsite->id,
                        'status' => $status,
                        'created_at' => $createdDate,
                        'user_id' => 3,
                    ]);
                }
            }
        }
    }
}
