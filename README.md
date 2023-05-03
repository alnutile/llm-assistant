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
    * Backups DB (already setup just make sure your server is there)
    * Email (postmark - see notes below)
    * Spatie files backups
    * Admin Accounts (see notes below)
  * Envoyer
    * I will set this up for you (sent you invite to this project to use as a template)
  * Postmark
    * The server is setup I can share a token
    * MAIL_FROM_ADDRESS="no-reply@break9.com"
    * MAIL_FROM_NAME="${APP_NAME}"
    * php artisan break:test_email will send test
  * Security Portal
    * I can get you a token for this
  * Backups
    * Database
      * This used Forge just make sure the db is added to the dbs
    * Digital Ocean Spaces for files backups
  * Laravel Shift
    * Let's talk about this
  * Horizon is setup 
    * Make sure the daemon is running on Forge just look at the other sites
    * /horizon works if your user is set to `is_admin` in the db
  * Logs
    * /log-viewer works if your user is set to `is_admin` in the db
  * 

### Postmark

Already installed set `.env`

```dotenv
POSTMARK_TOKEN=I_WILL_GIVE_THI
MAIL_MAILER=postmark
MAIL_FROM_ADDRESS="no-reply@break9.io"
MAIL_FROM_NAME="${APP_NAME}"
```

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
