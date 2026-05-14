# Deployment Guide: Render + Vercel

This guide walks you through deploying the Wardrobe application using Render for the backend and Vercel for the frontend.

## Part 1: Prepare Your Project

### Step 1: Generate Laravel App Key

```bash
cd backend
php artisan key:generate
```

Copy the generated key (format: `base64:xxxxxxxxxxxx`) - you'll need this for Render.

### Step 2: Update Configuration Files

The following files have been created with templates:

- `backend/.env.production` - Production environment variables (update URLs)
- `frontend/.env.production` - Production API URL (update after backend deployment)
- `frontend/.env.development` - Development API URL

### Step 3: Test Frontend Build Locally

```bash
cd frontend
npm run build
```

Verify the `dist/` folder is created successfully.

### Step 4: Push to GitHub

Ensure your repository is on GitHub with all changes committed:

```bash
git add .
git commit -m "Add deployment configuration files"
git push origin main
```

---

## Part 2: Deploy Backend on Render

### Step 1: Create Render Account

1. Go to [render.com](https://render.com)
2. Sign up with GitHub
3. Click "New +" → "Web Service"

### Step 2: Connect Your Repository

1. Select your GitHub account
2. Choose the `wardrobe` repository
3. Click "Connect"

### Step 3: Configure the Web Service

Fill in the following details:

| Field             | Value                                             |
| ----------------- | ------------------------------------------------- |
| **Name**          | wardrobe-api                                      |
| **Environment**   | PHP                                               |
| **Branch**        | main                                              |
| **Build Command** | `composer install && php artisan migrate --force` |
| **Start Command** | `vendor/bin/heroku-php-apache2 public/`           |
| **Plan**          | Free                                              |

### Step 4: Set Environment Variables

Click "Advanced" and add these environment variables:

```
APP_NAME = Wardrobe
APP_ENV = production
APP_DEBUG = false
APP_KEY = base64:YOUR_KEY_HERE (from Step 1 above)
LOG_CHANNEL = stack
LOG_LEVEL = error
DB_CONNECTION = pgsql
FRONTEND_URL = (leave empty for now, update after frontend deployment)
SESSION_DRIVER = database
CACHE_STORE = database
QUEUE_CONNECTION = database
```

### Step 5: Create PostgreSQL Database

1. On Render dashboard, click "New +" → "PostgreSQL"
2. Configure:
   - **Name**: wardrobe-db
   - **Database**: wardrobe
   - **User**: postgres
   - **Plan**: Free

3. Once created, copy the connection string

### Step 6: Add Database Credentials to Web Service

Update the web service environment variables with:

```
DB_HOST = (from PostgreSQL connection string)
DB_PORT = 5432
DB_DATABASE = wardrobe
DB_USERNAME = (from PostgreSQL)
DB_PASSWORD = (from PostgreSQL)
```

### Step 7: Deploy

Click "Deploy" and wait for the build to complete. You'll get a URL like:

```
https://wardrobe-api.onrender.com
```

**Note**: The first deployment may take 10-15 minutes.

### Step 8: Verify Backend

Test your API:

```bash
curl https://wardrobe-api.onrender.com/api/genders
# Should return an error requiring authentication (expected behavior)
```

---

## Part 3: Deploy Frontend on Vercel

### Step 1: Create Vercel Account

1. Go to [vercel.com](https://vercel.com)
2. Sign up with GitHub

### Step 2: Import Project

1. Click "Add New..." → "Project"
2. Select the `wardrobe` repository
3. Vercel will auto-detect it's a Vite project

### Step 3: Configure Build Settings

Set the following:

| Field                | Value                          |
| -------------------- | ------------------------------ |
| **Framework Preset** | Vite                           |
| **Build Command**    | `cd frontend && npm run build` |
| **Output Directory** | `frontend/dist`                |
| **Install Command**  | `npm install`                  |

### Step 4: Add Environment Variables

Add this environment variable:

```
VITE_API_URL = https://wardrobe-api.onrender.com/api
```

(Replace with your actual Render backend URL)

### Step 5: Deploy

Click "Deploy" and wait for completion. You'll get a URL like:

```
https://wardrobe.vercel.app
```

---

## Part 4: Link Backend & Frontend

### Step 1: Update Backend CORS

Update the `FRONTEND_URL` environment variable on Render:

```
FRONTEND_URL = https://wardrobe.vercel.app
```

This allows your frontend to make API requests from Vercel.

### Step 2: Redeploy Backend

On Render dashboard → wardrobe-api → "Manual Deploy" → "Deploy latest commit"

### Step 3: Test Integration

1. Visit your frontend: `https://wardrobe.vercel.app`
2. Try to register or login
3. Check browser network tab for API requests to your backend

---

## Monitoring & Troubleshooting

### View Logs

**Render Backend Logs:**

- Go to Render dashboard → wardrobe-api → "Logs"

**Vercel Frontend Logs:**

- Go to Vercel dashboard → wardrobe → "Deployments" → Select deployment → "Logs"

### Common Issues

| Issue                    | Solution                                               |
| ------------------------ | ------------------------------------------------------ |
| 403 CORS Error           | Check `FRONTEND_URL` in backend .env is correct        |
| API returns 500          | Check backend logs for database connection issues      |
| Build fails              | Ensure `package.json` paths are correct for Vite build |
| Database migration fails | Check PostgreSQL credentials and connection            |

### Manual Database Migration (if needed)

SSH into Render service and run:

```bash
php artisan migrate
php artisan db:seed
```

---

## Environment Variable Reference

### Backend (.env.production)

```env
APP_NAME=Wardrobe
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://wardrobe-api.onrender.com
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=wardrobe
DB_USERNAME=postgres
DB_PASSWORD=your-password
FRONTEND_URL=https://wardrobe.vercel.app
SESSION_DRIVER=database
CACHE_STORE=database
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Frontend (.env.production)

```env
VITE_API_URL=https://wardrobe-api.onrender.com/api
```

---

## Free Tier Limitations

- **Render**: Free web services spin down after 15 minutes of inactivity
- **Vercel**: Deployments are deployed on every push to main
- **PostgreSQL**: Limited to 1GB storage on free tier

---

## Next Steps (Optional)

1. **Set up custom domain** on Render and Vercel
2. **Enable automatic email verification** in backend
3. **Add CI/CD checks** before deploying
4. **Set up monitoring** and error tracking (Sentry)
