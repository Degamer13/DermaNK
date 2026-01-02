<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicamento; // <--- Importante importar el modelo

class MedicamentoSeeder extends Seeder
{
    public function run(): void
    {
        $medicamentos = [
            // --- ACNÉ ---
            [
                'patologia' => 'Acné',
                'nombre' => 'Doxiciclina',
                'tipo_medicamento' => 'Antibiótico Oral',
                'presentacion' => 'Caja x 30 tabletas (100mg)',
                'descripcion' => 'Tomar 1 tableta diaria después de la comida principal. Evitar exposición solar intensa.',
            ],
            [
                'patologia' => 'Acné',
                'nombre' => 'Isotretinoína (Roaccutan)',
                'tipo_medicamento' => 'Retinoide Oral',
                'presentacion' => 'Caja x 30 cápsulas (20mg)',
                'descripcion' => 'Uso estricto bajo supervisión médica. Requiere perfil hepático y lipídico mensual.',
            ],
            [
                'patologia' => 'Acné',
                'nombre' => 'Peróxido de Benzoilo',
                'tipo_medicamento' => 'Gel Tópico',
                'presentacion' => 'Tubo 60g (5%)',
                'descripcion' => 'Aplicar una capa fina por las noches sobre las lesiones. Puede desteñir la ropa.',
            ],
            [
                'patologia' => 'Acné',
                'nombre' => 'Clindamicina',
                'tipo_medicamento' => 'Loción Tópica',
                'presentacion' => 'Frasco 30ml (1%)',
                'descripcion' => 'Aplicar dos veces al día sobre el área afectada limpia y seca.',
            ],

            // --- ROSÁCEA ---
            [
                'patologia' => 'Rosácea',
                'nombre' => 'Ivermectina (Soolantra)',
                'tipo_medicamento' => 'Crema',
                'presentacion' => 'Tubo 30g (1%)',
                'descripcion' => 'Aplicar una vez al día en las zonas con rojez o pápulas. Ayuda a reducir el eritema.',
            ],
            [
                'patologia' => 'Rosácea',
                'nombre' => 'Metronidazol',
                'tipo_medicamento' => 'Gel',
                'presentacion' => 'Tubo 30g (0.75%)',
                'descripcion' => 'Aplicar una capa delgada por la noche. Evitar el consumo de alcohol durante el tratamiento.',
            ],

            // --- DERMATITIS ---
            [
                'patologia' => 'Dermatitis',
                'nombre' => 'Hidrocortisona',
                'tipo_medicamento' => 'Crema Corticosteroide',
                'presentacion' => 'Tubo 15g (1%)',
                'descripcion' => 'Uso por periodos cortos (máximo 7 días) para aliviar picazón e inflamación leve.',
            ],
            [
                'patologia' => 'Dermatitis Seborreica',
                'nombre' => 'Ketoconazol',
                'tipo_medicamento' => 'Champú',
                'presentacion' => 'Frasco 120ml (2%)',
                'descripcion' => 'Usar 2 o 3 veces por semana. Dejar actuar 5 minutos antes de enjuagar.',
            ],
            [
                'patologia' => 'Dermatitis Atópica',
                'nombre' => 'Tacrolimus',
                'tipo_medicamento' => 'Ungüento',
                'presentacion' => 'Tubo 30g (0.1%)',
                'descripcion' => 'Alternativa a corticoides. Aplicar capa fina en zonas afectadas. Evitar sol.',
            ],

            // --- MELASMA / MANCHAS ---
            [
                'patologia' => 'Melasma',
                'nombre' => 'Ácido Tranexámico',
                'tipo_medicamento' => 'Oral / Tópico',
                'presentacion' => 'Tabletas 250mg',
                'descripcion' => 'Tratamiento coadyuvante para manchas resistentes. Tomar según indicación exacta.',
            ],
            [
                'patologia' => 'Melasma',
                'nombre' => 'Hidroquinona',
                'tipo_medicamento' => 'Crema Despigmentante',
                'presentacion' => 'Tubo 30g (4%)',
                'descripcion' => 'Aplicar estrictamente de noche sobre la mancha. Uso obligatorio de protector solar de día.',
            ],

            // --- ALOPECIA ---
            [
                'patologia' => 'Alopecia',
                'nombre' => 'Minoxidil',
                'tipo_medicamento' => 'Loción Capilar',
                'presentacion' => 'Frasco Spray 60ml (5%)',
                'descripcion' => 'Aplicar 6 pulverizaciones en el cuero cabelludo seco cada noche. Masajear suavemente.',
            ],
        ];

        foreach ($medicamentos as $med) {
            Medicamento::create($med);
        }
    }
}
