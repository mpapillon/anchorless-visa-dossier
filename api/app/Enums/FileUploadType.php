<?php

namespace App\Enums;

enum FileUploadType: string
{
    case VisaRequestForm = 'visa_request_form';
    case Photo = 'photo';
    case Passport = 'passport';
}
