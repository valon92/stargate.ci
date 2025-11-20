# Voice Actions SDK - Debugging Guide

## Problemi: Voice Actions nuk po vepron kur flasim nga mikrofoni

### Hapat për Debugging:

1. **Kontrollo nëse SDK-ja është inicializuar:**
   - Hap Console në browser (F12)
   - Shiko për mesazhin: `✅ Voice Actions SDK initialized`
   - Kontrollo nëse ka 19 komanda të ngarkuara

2. **Kontrollo nëse butoni i voice control është i dukshëm:**
   - Butoni duhet të jetë në këndin e poshtëm djathtas
   - Duhet të jetë i aktivizuar (jo disabled)

3. **Kontrollo lejen e mikrofonit:**
   - Kliko butonin e voice control
   - Browser-i duhet të shfaqë një prompt për leje për mikrofonin
   - Nëse nuk shfaqet, kontrollo settings e browser-it

4. **Kontrollo Console për mesazhe:**
   - Kur klikon butonin, duhet të shohësh: `🎤 Starting voice recognition...`
   - Nëse ka sukses: `✅ Voice recognition started successfully`
   - Nëse ka gabim: `❌ Failed to start voice listening:`

5. **Kontrollo nëse Speech Recognition API është e mbështetur:**
   - Në Console, shkruaj: `'webkitSpeechRecognition' in window || 'SpeechRecognition' in window`
   - Duhet të kthejë `true`

6. **Testo me komanda të thjeshta:**
   - Kur butoni është aktiv (i kuq), provo të thuash:
     - "scroll down"
     - "go home"
     - "go to events"
   - Shiko në Console për mesazhe: `🎤 onCommand callback triggered`

### Komanda të disponueshme:

- **Navigation:**
  - "go home", "home", "home page"
  - "go to events", "events", "events page"
  - "go to news", "news", "news page"
  - "go to about", "about", "about page"
  - "go to faq", "faq", "faq page"
  - "go to contact", "contact", "contact page"
  - "go to subscribe", "subscribe"
  - "go to search", "search"

- **Scroll:**
  - "scroll down", "go down", "move down"
  - "scroll up", "go up", "move up"
  - "scroll to top", "go to top"
  - "scroll to bottom", "go to bottom"

### Problemet e mundshme:

1. **Browser nuk mbështet Speech Recognition:**
   - Chrome/Edge: ✅ Mbështet
   - Safari: ✅ Mbështet
   - Firefox: ❌ Nuk mbështet

2. **Leja e mikrofonit nuk është dhënë:**
   - Kontrollo settings e browser-it
   - Jep leje për mikrofonin për localhost

3. **SDK-ja nuk po detekton zërin:**
   - Kontrollo nëse mikrofoni po funksionon
   - Testo në aplikacione të tjera

4. **Komandat nuk po ekzekutohen:**
   - Kontrollo nëse frazat që thua përputhen me komandat
   - Shiko në Console për mesazhe të detajuara

5. **Network Error nga Speech Recognition:**
   - **Problemi:** `Network error. Please check your internet connection.`
   - **Shkaku:** Speech Recognition API nuk mund të lidhet me shërbimin e Google
   - **Zgjidhje:**
     - Kontrollo nëse keni internet connection
     - Provoni të rifreskoni faqen dhe të provoni përsëri
     - Në disa raste, Speech Recognition mund të kërkojë HTTPS (por localhost duhet të funksionojë)
     - Nëse problemi vazhdon, provoni në një browser tjetër (Chrome ose Edge)
     - Në disa raste, mund të jetë një problem i përkohshëm me shërbimin e Google Speech Recognition

### Logging i detajuar:

Tani kemi shtuar logging më të detajuar:
- `🎤 Starting voice recognition...` - Kur fillon dëgjimi
- `✅ Voice recognition started successfully` - Kur fillon me sukses
- `❌ Failed to start voice listening:` - Kur ka gabim
- `🎤 onCommand callback triggered:` - Kur detektohet një komandë
- `🎤 Voice command received:` - Kur merret komanda
- `🎤 Executing action:` - Kur ekzekutohet komanda

### Hapat për të testuar:

1. Hap browser (Chrome ose Edge)
2. Shko në `http://localhost:5173`
3. Hap Console (F12)
4. Kliko butonin e voice control (këndi i poshtëm djathtas)
5. Jep leje për mikrofonin nëse kërkohet
6. Thuaj një komandë (p.sh. "scroll down")
7. Shiko në Console për mesazhe

### Zgjidhja për Network Error:

Nëse shfaqet gabimi "Network error":
1. **Kontrollo internet connection** - Speech Recognition kërkon internet për të funksionuar
2. **Rifresko faqen** dhe provo përsëri
3. **Prit pak** - Ndonjëherë shërbimi i Google Speech Recognition mund të jetë i zënë
4. **Provo në browser tjetër** - Chrome ose Edge janë më të besueshëm
5. **Kontrollo firewall/antivirus** - Mund të bllokojnë lidhjen me shërbimin e Google

**Shënim:** Speech Recognition API në Chrome/Edge përdor shërbimin e Google për të përpunuar zërin, kështu që kërkon internet connection dhe lidhje me Google servers.

