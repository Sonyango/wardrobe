# Deployment Guide: Railway + Vercel

This guide walks you through deploying the Wardrobe application using Railway for the backend and Vercel for the frontend.

---

## Part 1: Prepare Your Project

### Step 1: Verify Laravel App Key

The app key has already been generated and is in `backend/.env`:

```
APP_KEY=base64:9NfaRbLwtYww/MopAKvgCFqxbGILQBZzzkIzMCiifTo=
```

Keep this safe—you'll need it for Railway environment variables.

### Step 2: Update Configuration Files

The following files have been created with templates:

- `backend/.env.production` - Production environment variables
- `frontend/.env.production` - Production API URL
- `frontend/.env.development` - Development API URL

### Step 3: Test Frontend Build Locally

```bash
cd frontend
npm run build
```

Verify the `dist/` folder is created successfully.

### Step 4: Ensure Code is Pushed to GitHub

```bash
cd /home/stephen/Projects/wardrobe
git add .
git commit -m "Add deployment configurations"
git push origin main
```

---

## Part 2: Deploy Backend on Railway

### Step 1: Create Railway Account

1. Go to [railway.app](https://railway.app)
2. Click **"Start Project"**
3. Sign up with GitHub
4. Authorize Railway to access your repositories

### Step 2: Create New Project

1. Click **"New Project"** on your Railway dashboard
2. Select **"Deploy from GitHub repo"**
3. Search for and select your `wardrobe` repository
4. Click **"Deploy"**

Railway will automatically detect it's a Laravel project and set it up.

### Step 3: Add PostgreSQL Database

1. Click **"+ Add Service"** in your Railway project
2. Search for **"PostgreSQL"**
3. Click to add it
4. Railway creates a database automatically

### Step 4: Configure Environment Variables

Once services are added, click on the **web service** (wardrobe) and go to the **"Variables"** tab.

Add/Update these variables:

```env
APP_NAME=Wardrobe
APP_ENV=production
APP_KEY=base64:9NfaRbLwtYww/MopAKvgCFqxbGILQBZzzkIzMCiifTo=
APP_DEBUG=false
APP_TIMEZONE=Africa/Nairobi
LOG_CHANNEL=stack
LOG_LEVEL=error
DB_CONNECTION=pgsql
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=log
FRONTEND_URL=(leave empty for now, update after frontend deployment)
```

**Database Variables** (Railway provides these automatically, but verify they're set):

Click on the **PostgreSQL** service and go to **"Variables"** tab. You should see:

- `DATABASE_URL` (Railway creates this automatically)

Railway automatically connects these to your web service, so you don't need to manually set `DB_HOST`, `DB_PORT`, etc.

### Step 5: Configure Build & Deploy Settings

Click on the **web service** and go to the **"Settings"** tab:

- **Build Command**: `composer install --no-dev && php artisan migrate --force`
- **Start Command**: Leave as default (Railway handles this for Laravel)

### Step 6: Deploy

Click the **"Deploy"** button. You'll see deployment logs. Wait for it to complete (~5-10 minutes).

Once deployed, you'll get a URL like:

```
https://wardrobe-production-xyz.up.railway.app
```

### Step 7: Verify Backend

Test your API endpoint:

```bash
curl https://wardrobe-production-xyz.up.railway.app/api/genders
```

Should return an error requiring authentication (expected).

---

## Part 3: Deploy Frontend on Vercel

### Step 1: Create Vercel Account

1. Go to [vercel.com](https://vercel.com)
2. Click **"Sign Up"**
3. Sign up with GitHub
4. Authorize Vercel

### Step 2: Import Project

1. Click **"Add New..."** → **"Project"**
2. Click **"Import Git Repository"**
3. Search for and select your `wardrobe` repository
4. Click **"Import"**

### Step 3: Configure Build Settings

In the **"Configure Project"** screen, set:

| Field                | Value                          |
| -------------------- | ------------------------------ |
| **Framework Preset** | Vite                           |
| **Build Command**    | `cd frontend && npm run build` |
| **Output Directory** | `frontend/dist`                |
| **Install Command**  | `npm install`                  |

### Step 4: Add Environment Variables

Under **"Environment Variables"**, add:

```
VITE_API_URL = https://wardrobe-production-xyz.up.railway.app/api
```

Replace `wardrobe-production-xyz.up.railway.app` with your actual Railway backend URL.

### Step 5: Deploy

Click **"Deploy"**. Wait ~5-10 minutes. You'll get a URL like:

```
https://wardrobe.vercel.app
```

### Step 6: Verify Frontend

Visit `https://wardrobe.vercel.app` in your browser. The frontend should load.

---

## Part 4: Link Backend & Frontend

### Step 1: Update Backend CORS

Go back to **Railway Dashboard** → Select your **wardrobe web service** → **"Variables"** tab.

Update:

```
FRONTEND_URL=https://wardrobe.vercel.app
```

(Use your actual Vercel frontend URL)

### Step 2: Redeploy Backend

Click the **"Deploy"** button to redeploy with the new environment variable.

### Step 3: Test Integration

1. Visit your frontend: `https://wardrobe.vercel.app`
2. Try to **register** or **login**
3. Open browser **Developer Tools** → **Network** tab
4. Check that API requests go to your Railway backend
5. You should see successful API responses

---

## Part 5: Environment Variable Reference

### Backend Environment Variables (Railway)

```env
APP_NAME=Wardrobe
APP_ENV=production
APP_KEY=base64:9NfaRbLwtYww/MopAKvgCFqxbGILQBZzzkIzMCiifTo=
APP_DEBUG=false
APP_TIMEZONE=Africa/Nairobi
APP_URL=https://wardrobe-production-xyz.up.railway.app
LOG_CHANNEL=stack
LOG_LEVEL=error
DB_CONNECTION=pgsql
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
FRONTEND_URL=https://wardrobe.vercel.app
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=(your Mailtrap username)
MAIL_PASSWORD=(your Mailtrap password)
MAIL_FROM_ADDRESS=noreply@wardrobe.com
MAIL_FROM_NAME=Wardrobe
```

### Frontend Environment Variables (Vercel)

```env
VITE_API_URL=https://wardrobe-production-xyz.up.railway.app/api
```

---

## Monitoring & Troubleshooting

### View Logs

**Railway Backend Logs:**

- Dashboard → Select web service → **"Logs"** tab

**Vercel Frontend Logs:**

- Dashboard → Select project → **"Deployments"** → Select deployment → **"Logs"**

### Common Issues

| Issue                         | Solution                                                                   |
| ----------------------------- | -------------------------------------------------------------------------- |
| **403 CORS Error**            | Check `FRONTEND_URL` in Railway variables is exactly correct               |
| **API returns 500**           | Check Railway backend logs for database connection errors                  |
| **Frontend build fails**      | Verify `package.json` has correct paths for Vite build in frontend folder  |
| **Database migration fails**  | Check PostgreSQL is connected; Railway logs should show connection error   |
| **Frontend shows blank page** | Check browser console for `VITE_API_URL` errors; verify it's set in Vercel |

### Manual Database Seeding (if needed)

If migrations fail to run automatically:

1. Go to **Railway Dashboard** → Select **web service** → Click **"Logs"**
2. Look for migration errors
3. In Railway, click **"Terminal"** (if available in the service)
4. Run: `php artisan migrate --force && php artisan db:seed --force`

Alternatively, use Railway's CLI:

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link to your project
railway link

# Run migrations
railway run php artisan migrate --force
railway run php artisan db:seed --force
```

### Check Database Connection

```bash
# Via Railway CLI
railway run php artisan tinker
# Then in Tinker:
>>> DB::connection()->getPdo()
```

---

## Free Tier Limitations

- **Railway**: Free tier includes $5/month credit (usually covers small projects)
- **Vercel**: Unlimited free deployments on Hobby plan
- **PostgreSQL**: Included in Railway free tier

---

## Next Steps (Optional)

1. **Set up custom domain** on Railway and Vercel
2. **Configure email service** (Mailtrap is already configured in `.env`)
3. **Set up error tracking** (Sentry integration)
4. **Enable GitHub Actions** for CI/CD pipeline
5. **Add monitoring** (Uptime monitoring for backend health)

---

## Useful Railway & Vercel Commands

```bash
# Railway CLI
railway login                    # Authenticate
railway link                     # Link to project
railway run php artisan ...      # Run Artisan commands
railway logs                     # View logs

# View service details
railway status
railway logs --follow            # Real-time logs
```

---

## Support Resources

- Railway Docs: https://docs.railway.app
- Vercel Docs: https://vercel.com/docs
- Laravel Deployment: https://laravel.com/docs/11.x/deployment
- Vue Router: https://router.vuejs.org/
