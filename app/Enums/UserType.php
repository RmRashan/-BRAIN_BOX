<?php
namespace App\Enums;

enum UserType: string
{
    case Admin = 'admin';
    case Staff = 'staff';
    case Instructor = 'instructor';
    case Student = 'student';
    case HEStudent = 'he_student';
    case Agent = 'agent';

    public static function values(): array
    {
        return self::cases();
    }
}
