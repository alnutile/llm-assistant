## Break 9 Water App

[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F672a55d7-caee-4ce2-bed1-eba6e0c185f2%3Fdate%3D1%26commit%3D1&style=flat)](https://forge.laravel.com/servers/662538/sites/1970721)


[![CI-CD](https://github.com/Break9dev/clean-water/actions/workflows/ci-cd.yml/badge.svg)](https://github.com/Break9dev/clean-water/actions/workflows/ci-cd.yml)


## CI / CD

Envoyer will be triggered from the `.github/workspace/ci-cd.yml`

## Setup

### New Servers
  * Forge
    * SSL
    * Daemon for Horizon
    * Backups DB 
    * Email (postmark)
    * Spatie files backups
    * Admin Accounts
  * Envoyer
  * Postmark
  * Security Portal
  * Backups
    * Database
    * Spaces for files


### Seed Admins 

You can run this again if needed

Update `.env`

```dotenv
ADMIN_ONE=bobbelcher@gmail.com
ADMIN_ONE_PASSWORD=makeagoodone

ADMIN_TWO=bobbelcher+101@gmail.com
ADMIN_TWO_PASSWORD=makeagoodone
```

```bash 
php artisan db:seed --class=AdminSeeder
```
