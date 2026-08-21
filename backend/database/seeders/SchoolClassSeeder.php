<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run()
    {
        for ($grade = 1; $grade <= 5; $grade++) {

            for ($classNumber = 1; $classNumber <= 3; $classNumber++) {

                $className =
                    $grade . 'A' . $classNumber;

                SchoolClass::updateOrCreate(
                    [
                        'class_name' => $className,
                    ],
                    [
                        'grade' => (string) $grade,
                        'student_count' => 0,
                        'status' => 'Đang hoạt động',
                    ]
                );
            }
        }
    }
}