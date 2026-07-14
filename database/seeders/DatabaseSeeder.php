<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\CommunityWorker;
use App\Models\Mark;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@sms.local',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '0700000001',
            'is_active' => true,
        ]);

        // Create Teachers
        $teacher1User = User::create([
            'name' => 'Mr. James Mukasa',
            'email' => 'teacher1@sms.local',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'phone' => '0700000002',
            'is_active' => true,
        ]);

        $teacher2User = User::create([
            'name' => 'Mrs. Sarah Nakayima',
            'email' => 'teacher2@sms.local',
            'password' => Hash::make('password123'),
            'role' => 'teacher',
            'phone' => '0700000003',
            'is_active' => true,
        ]);

        // Create Community Workers
        $worker1 = CommunityWorker::create([
            'name' => 'John Opiyo',
            'phone' => '0701111111',
            'email' => 'john.opiyo@nansana.local',
            'zone' => 'Nansana East',
            'address' => 'Nansana East Zone',
            'is_active' => true,
        ]);

        $worker2 = CommunityWorker::create([
            'name' => 'Mary Kiwanuka',
            'phone' => '0701111112',
            'email' => 'mary.kiwanuka@nansana.local',
            'zone' => 'Nansana West',
            'address' => 'Nansana West Zone',
            'is_active' => true,
        ]);

        // Create Classes for 2026
        $classes = [];
        $classNames = ['P.1', 'P.2', 'P.3', 'P.4', 'P.5', 'P.6', 'P.7'];
        
        foreach ($classNames as $index => $className) {
            $teacher = $index == 4 ? $teacher1User : $teacher2User;
            $classes[$className] = ClassModel::create([
                'class_name' => $className,
                'academic_year' => 2026,
                'teacher_id' => $teacher->id,
                'total_students' => 0,
                'is_active' => true,
            ]);
        }

        // Create Guardians
        $guardians = [];
        $guardianNames = [
            ['name' => 'Peter Kiprotich', 'phone' => '0702000001', 'relationship' => 'Father'],
            ['name' => 'Grace Kipchoge', 'phone' => '0702000002', 'relationship' => 'Mother'],
            ['name' => 'David Ochieng', 'phone' => '0702000003', 'relationship' => 'Uncle'],
            ['name' => 'Rose Otieno', 'phone' => '0702000004', 'relationship' => 'Aunt'],
            ['name' => 'James Mwangi', 'phone' => '0702000005', 'relationship' => 'Father'],
        ];

        foreach ($guardianNames as $guardianData) {
            $guardians[] = Guardian::create($guardianData);
        }

        // Create Students
        $studentNames = [
            'John Peter', 'Sarah Mary', 'David James', 'Michael Paul', 'Elizabeth Jane',
            'Joseph Kipchoge', 'Mary Ochieng', 'Daniel Mwangi', 'Ruth Kiprotich', 'Samuel Otieno',
            'Grace Kipchoge', 'Patrick Nkomo', 'Catherine Ndlela', 'Moses Banda', 'Linda Chuma',
            'Christopher Kapoor', 'Rachel Kumar', 'Thomas Okonkwo', 'Margaret Owusu', 'Steven Kwame',
            'Victoria Mensah', 'Andrew Asiimwe', 'Helen Otim', 'Kenneth Musoke', 'Rebecca Mukama',
            'Francis Kabwama', 'Margaret Bbale', 'Gregory Ssempa', 'Dorothy Kaweesi', 'Leonard Luyinda',
            'Pauline Nakacwa', 'Peter Bwire', 'Susan Namukasa', 'George Kalema', 'Angela Mutonyi',
            'Robert Nanteza', 'Patricia Kiggira', 'William Ssensalire', 'Christine Lubega', 'Isaac Katongole',
            'Beatrice Kusemererwa', 'Vincent Ssekandi', 'Florence Kamicho', 'Martin Muwanga', 'Joyce Kyando',
            'Charles Sekitoleko', 'Theresa Katende', 'Arthur Mwase', 'Hannah Nsereko'
        ];

        $studentId = 1;
        $classIndex = 0;
        
        foreach ($studentNames as $fullName) {
            [$surname, $otherNames] = explode(' ', $fullName, 2);
            $classLevel = $classNames[$classIndex % 7];
            
            $student = Student::create([
                'student_id' => 'STD' . str_pad($studentId, 3, '0', STR_PAD_LEFT),
                'surname' => $surname,
                'other_names' => $otherNames,
                'gender' => $studentId % 2 === 0 ? 'Female' : 'Male',
                'date_of_birth' => now()->subYears(rand(5, 12))->subMonths(rand(0, 11))->subDays(rand(1, 28)),
                'photo_path' => null,
                'entry_year' => 2024,
                'class_id' => $classes[$classLevel]->id,
                'status' => 'Active',
                'guardian_id' => $guardians[array_rand($guardians)]->id,
                'community_worker_id' => $worker1->id ?? $worker2->id,
                'zone' => $studentId % 2 === 0 ? 'Nansana East' : 'Nansana West',
            ]);

            // Create sample marks for each student
            $subjects = ['Mathematics', 'English', 'Science', 'Social Studies', 'Religious Education', 'Local Language'];
            
            foreach ($subjects as $subject) {
                Mark::create([
                    'student_id' => $student->id,
                    'subject' => $subject,
                    'academic_year' => 2026,
                    'term' => 1,
                    'test_1_score' => rand(40, 100),
                    'test_2_score' => rand(40, 100),
                    'assignment_score' => rand(50, 100),
                    'exam_score' => rand(30, 100),
                    'teacher_id' => $teacher1User->id,
                ]);
            }

            // Create scholarship for some students
            if ($studentId % 3 === 0) {
                Scholarship::create([
                    'student_id' => $student->id,
                    'has_scholarship' => true,
                    'scholarship_type' => ['Secondary School', 'University', 'Other'][array_rand(['Secondary School', 'University', 'Other'])],
                    'sponsor_name' => ['Ashinaga Scholarship', 'World Vision', 'Local NGO'][array_rand(['Ashinaga Scholarship', 'World Vision', 'Local NGO'])],
                    'sponsor_contact' => 'sponsor@example.com',
                    'amount' => rand(100000, 500000),
                    'currency' => 'UGX',
                    'start_year' => 2026,
                    'end_year' => 2030,
                    'status' => 'Active',
                    'notes' => 'Active scholarship sponsorship',
                ]);
            } else {
                Scholarship::create([
                    'student_id' => $student->id,
                    'has_scholarship' => false,
                ]);
            }

            $studentId++;
            $classIndex++;
        }

        echo "Database seeded successfully!\n";
        echo "Total students created: " . count($studentNames) . "\n";
        echo "Default credentials:\n";
        echo "Admin: admin@sms.local / password123\n";
        echo "Teacher 1: teacher1@sms.local / password123\n";
        echo "Teacher 2: teacher2@sms.local / password123\n";
    }
}
