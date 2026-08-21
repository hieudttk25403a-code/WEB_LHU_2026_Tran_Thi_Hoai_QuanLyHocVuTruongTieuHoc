<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            [
                'subject_code' => 'TV',
                'subject_name' => 'Tiếng Việt',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'TOAN',
                'subject_name' => 'Toán',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'DD',
                'subject_name' => 'Đạo đức',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'TNXH',
                'subject_name' => 'Tự nhiên và Xã hội',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'LS-DL',
                'subject_name' => 'Lịch sử và Địa lý',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'NN1',
                'subject_name' => 'Ngoại ngữ 1',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'TH-CN',
                'subject_name' => 'Tin học và Công nghệ',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'GDTC',
                'subject_name' => 'Giáo dục thể chất',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'AN',
                'subject_name' => 'Âm nhạc',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'MT',
                'subject_name' => 'Mĩ thuật',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
            [
                'subject_code' => 'HDTN',
                'subject_name' => 'Hoạt động trải nghiệm',
                'grade' => '1,2,3,4,5',
                'status' => 'Đang hoạt động',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                [
                    'subject_code' => $subject['subject_code'],
                ],
                $subject
            );
        }
    }
}