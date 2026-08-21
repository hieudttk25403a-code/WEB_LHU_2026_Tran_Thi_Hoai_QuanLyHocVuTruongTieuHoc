<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\StudentClassHistory;
use Illuminate\Console\Command;

class SyncStudentClassHistories extends Command
{
    protected $signature = 'students:sync-class-histories';

    protected $description = 'Đồng bộ lớp hiện tại của học sinh vào lịch sử lớp';

    public function handle()
    {
        $schoolYear = SchoolYear::where('is_active', 1)->first();

        if (!$schoolYear) {
            $this->error('Chưa có năm học đang hoạt động.');
            return Command::FAILURE;
        }

        $students = Student::whereNotNull('class_id')->get();

        if ($students->isEmpty()) {
            $this->info('Không có học sinh nào có lớp hiện tại.');
            return Command::SUCCESS;
        }

        $created = 0;
        $skipped = 0;

        foreach ($students as $student) {

            $exists = StudentClassHistory::where('student_id', $student->id)
                ->where('class_id', $student->class_id)
                ->where('school_year_id', $schoolYear->id)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            StudentClassHistory::create([
                'student_id' => $student->id,
                'class_id' => $student->class_id,
                'school_year_id' => $schoolYear->id,
                'start_date' => $schoolYear->start_date ?? now()->toDateString(),
                'end_date' => null,
                'note' => 'Đồng bộ từ dữ liệu học sinh hiện tại.',
                'status' => 'Đang học',
            ]);

            $created++;
        }

        $this->info("Đã tạo {$created} lịch sử lớp.");

        $this->info("Đã bỏ qua {$skipped} bản ghi đã tồn tại.");

        return Command::SUCCESS;
    }
}