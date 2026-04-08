<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SusQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = [
            'Saya pikir saya akan sering menggunakan sistem ini.',
            'Saya menemukan sistem ini tidak perlu rumit.',
            'Saya rasa sistem ini mudah digunakan.',
            'Saya butuh bantuan orang teknis untuk menggunakan sistem ini.',
            'Saya menemukan berbagai fungsi dalam sistem ini terintegrasi dengan baik.',
            'Saya pikir ada terlalu banyak ketidakkonsistenan dalam sistem ini.',
            'Saya membayangkan kebanyakan orang akan belajar menggunakan sistem ini dengan sangat cepat.',
            'Saya menemukan sistem ini sangat merepotkan untuk digunakan.',
            'Saya merasa sangat percaya diri menggunakan sistem ini.',
            'Saya perlu mempelajari banyak hal sebelum saya bisa mulai menggunakan sistem ini.',
        ];

        foreach ($questions as $index => $q) {
            \App\Models\SusQuestion::create([
                'question_text' => $q,
                'order' => $index + 1,
            ]);
        }
    }
}
