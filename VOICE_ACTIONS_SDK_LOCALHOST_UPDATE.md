# Voice Actions SDK - Përditësim nga Localhost

## 📋 Përmbledhje

Ky dokument shpjegon si të përditësohet dhe përdoret Voice Actions SDK nga localhost për zhvillim lokal.

---

## 🔄 Metodat e Përditësimit

### Metoda 1: Përditësim i Paketës NPM (Rekomanduar për Production)

Nëse dëshiron të përditësosh versionin e paketës nga npm registry:

```bash
cd frontend
npm update @valon92/voice-actions-sdk
```

Ose për të instaluar versionin më të ri:

```bash
cd frontend
npm install @valon92/voice-actions-sdk@latest
```

---

### Metoda 2: Përdorim i Versionit Lokal të SDK-së (Për Zhvillim)

Nëse po zhvillon SDK-në lokal dhe dëshiron ta përdorësh në vend të versionit nga npm:

#### Hapi 1: Linko Paketën Lokale

Nëse ke një version lokal të SDK-së në një folder tjetër:

```bash
# Në folder-in e SDK-së lokale
cd /path/to/voice-actions-sdk
npm link

# Në folder-in e frontend-it
cd /Users/valonsylejmani/Projekte/starget.ci/frontend
npm link @valon92/voice-actions-sdk
```

#### Hapi 2: Ose Përdor Path Lokal në package.json

Nëse SDK-ja lokale është në një folder relativ:

```json
{
  "dependencies": {
    "@valon92/voice-actions-sdk": "file:../voice-actions-sdk"
  }
}
```

Pastaj:

```bash
cd frontend
npm install
```

---

### Metoda 3: Përditësim i Konfigurimit për Localhost Backend

SDK-ja tashmë është konfiguruar për të përdorur localhost backend automatikisht. Por nëse dëshiron ta konfigurosh manualisht:

#### Në `frontend/src/stores/voiceActions.ts`:

```typescript
// Determine API URL - use local backend or demo mode
const isLocalhost = typeof window !== 'undefined' && window.location.hostname === 'localhost'
const apiUrl = isLocalhost 
  ? 'http://localhost:8000/api'  // Backend lokal
  : (typeof window !== 'undefined' ? `${window.location.origin}/api` : 'http://localhost:8000/api')
```

#### Verifikimi i Backend-it Lokal

Kontrollo që backend server është i startuar:

```bash
cd backend
php artisan serve
```

Backend duhet të jetë i aksesueshëm në `http://localhost:8000`

#### Testimi i Endpoint-it

```bash
# Test endpoint për komandat
curl http://localhost:8000/api/v1/commands?platform_name=stargate-ci

# Ose në browser
http://localhost:8000/api/v1/commands?platform_name=stargate-ci
```

---

## 🔧 Konfigurim i Detajuar

### 1. Environment Variables (Opsionale)

Mund të shtosh environment variables për konfigurim më fleksibël:

#### Në `.env` ose `.env.local`:

```env
VITE_VOICE_ACTIONS_API_URL=http://localhost:8000/api
VITE_VOICE_ACTIONS_API_KEY=your-api-key-here
```

#### Në `voiceActions.ts`:

```typescript
const apiUrl = import.meta.env.VITE_VOICE_ACTIONS_API_URL 
  || (isLocalhost ? 'http://localhost:8000/api' : `${window.location.origin}/api`)
```

---

### 2. Debug Mode për Localhost

Debug mode është aktivizuar automatikisht në development:

```typescript
sdk.value = new VoiceActionsSDK({
  apiKey: apiKey || undefined,
  apiUrl: apiUrl,
  platform: 'stargate-ci',
  locale: locale.value,
  debug: import.meta.env.DEV, // Automatikisht true në localhost
  // ...
})
```

---

## 🧪 Testimi i Përditësimit

### 1. Verifikimi i Versionit

```bash
cd frontend
npm list @valon92/voice-actions-sdk
```

### 2. Testimi i Funksionalitetit

1. **Starto backend:**
   ```bash
   cd backend
   php artisan serve
   ```

2. **Starto frontend:**
   ```bash
   cd frontend
   npm run dev
   ```

3. **Testo Voice Control:**
   - Hap browser në `http://localhost:5173` (ose porti i Vite)
   - Kliko butonin e mikrofonit
   - Jep leje për mikrofonin
   - Provo komandat si "scroll down", "go to events", etj.

### 3. Kontrollo Console për Gabime

Hap Developer Tools (F12) dhe shiko Console për:
- ✅ Mesazhe debug nga SDK
- ✅ Komandat e detektuara
- ❌ Gabime nëse ka

---

## 🔍 Troubleshooting

### Problemi: SDK nuk ngarkohet nga localhost

**Zgjidhja:**
1. Verifiko që backend server është i startuar
2. Kontrollo që endpoint `/api/v1/commands` funksionon
3. Shiko Network tab në Developer Tools për request-et

### Problemi: Versioni i vjetër përdoret ende

**Zgjidhja:**
```bash
cd frontend
rm -rf node_modules package-lock.json
npm install
```

### Problemi: npm link nuk funksionon

**Zgjidhja:**
1. Unlink versionin e vjetër:
   ```bash
   cd frontend
   npm unlink @valon92/voice-actions-sdk
   ```

2. Reinstall nga npm:
   ```bash
   npm install @valon92/voice-actions-sdk@latest
   ```

---

## 📝 Checklist për Përditësim

- [ ] Backend server është i startuar (`php artisan serve`)
- [ ] Frontend dev server është i startuar (`npm run dev`)
- [ ] Endpoint `/api/v1/commands` kthen komandat
- [ ] SDK inicializohet pa gabime
- [ ] Mikrofoni ka leje
- [ ] Komandat e zërit funksionojnë
- [ ] Console nuk tregon gabime

---

## 🚀 Komanda të Shpejta

```bash
# Përditëso SDK nga npm
cd frontend && npm update @valon92/voice-actions-sdk

# Ose install versionin më të ri
cd frontend && npm install @valon92/voice-actions-sdk@latest

# Starto backend
cd backend && php artisan serve

# Starto frontend
cd frontend && npm run dev

# Test endpoint
curl http://localhost:8000/api/v1/commands?platform_name=stargate-ci
```

---

## 📚 Burime

- [Voice Actions SDK GitHub](https://github.com/valon92/voice-actions-sdk)
- [npm link Documentation](https://docs.npmjs.com/cli/v8/commands/npm-link)
- [Vite Environment Variables](https://vitejs.dev/guide/env-and-mode.html)

---

**Dokumenti i krijuar:** 2025-01-29  
**Status:** ✅ Udhëzime të plota për përditësim nga localhost

