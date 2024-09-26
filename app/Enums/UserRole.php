<?php
namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Staff = 'staff';
    case Instructor = 'instructor';
    case Student = 'student';
    case HEStudent = 'he_student';
    case Agent = 'agent';
}
