<?php

namespace App\Enum;

enum UserRoleEnum: string {
  case Admin = 'admin';
  case WebsiteAdmin = 'website_admin';
  case WebsiteUser = 'website_user';
}
