# Stargate.ci - Veçoritë e Platformës për Voice Actions SDK

## 📋 Përmbledhje

Ky dokument përmbledh të gjitha veçoritë dhe funksionalitetet e platformës Stargate.ci që mund të integrohen në Voice Actions SDK për kontroll me zë.

---

## 🗺️ Navigim (Navigation)

### Faqet Kryesore

1. **Home** (`/`)
   - Frazat: "go home", "home", "home page", "main page", "go to home", "take me home"
   - Përshkrimi: Faqja kryesore me video edukative dhe informacione rreth Stargate Project

2. **About** (`/about`)
   - Frazat: "go to about", "about", "about page", "about us", "show about", "tell me about"
   - Përshkrimi: Informacione rreth misionit, vizionit dhe projektit

3. **Events** (`/events`)
   - Frazat: "go to events", "events", "events page", "show events", "view events", "upcoming events"
   - Përshkrimi: Lista e eventeve dhe takimeve rreth Stargate Project dhe Cristal Intelligence

4. **News** (`/news`)
   - Frazat: "go to news", "news", "news page", "show news", "latest news", "read news"
   - Përshkrimi: Lajmet e fundit nga OpenAI, SoftBank, Arm dhe burime të tjera relevante

5. **FAQ** (`/faq`)
   - Frazat: "go to faq", "faq", "faq page", "frequently asked questions", "show faq", "help"
   - Përshkrimi: Pyetjet më të shpeshta dhe përgjigjet

6. **Contact** (`/contact`)
   - Frazat: "go to contact", "contact", "contact page", "contact us", "get in touch"
   - Përshkrimi: Formulari i kontaktit

7. **Subscribe** (`/subscribe`)
   - Frazat: "go to subscribe", "subscribe", "subscribe page", "sign up for updates"
   - Përshkrimi: Sistem i abonimit për njoftime

8. **Search** (`/search`)
   - Frazat: "go to search", "search", "search page", "open search", "show search"
   - Përshkrimi: Faqja e kërkimit

9. **Disclaimer** (`/disclaimer`)
   - Frazat: "go to disclaimer", "disclaimer", "legal disclaimer", "show disclaimer"
   - Përshkrimi: Shënim ligjor

### Autentifikim (Authentication)

10. **Sign In** (`/signin`)
    - Frazat: "go to sign in", "sign in", "sign in page", "login", "log in", "sign in to account"
    - Përshkrimi: Hyrje në llogari

11. **Sign Up** (`/signup`)
    - Frazat: "go to sign up", "sign up", "sign up page", "register", "create account", "new account"
    - Përshkrimi: Regjistrim i ri

---

## 🎬 Interaksione me Video (Video Interactions)

### Komanda për Video

12. **Like Video**
    - Frazat: "like this video", "like video", "thumbs up", "i like this"
    - Përshkrimi: Shto like në video aktive

13. **Comment on Video**
    - Frazat: "add comment", "comment", "write comment", "post comment"
    - Përshkrimi: Hap formën për komentim (kërkon fokus në input field)

14. **Share Video**
    - Frazat: "share video", "share this", "share", "share content"
    - Përshkrimi: Shpërnda video

15. **Play/Pause Video**
    - Frazat: "play video", "pause video", "stop video", "resume video"
    - Përshkrimi: Kontrollo riprodhimin e videos (nëse është video native, jo YouTube)

---

## 📰 Interaksione me Lajme (News Interactions)

16. **Like Article**
    - Frazat: "like article", "like this article", "thumbs up article"
    - Përshkrimi: Shto like në artikull

17. **Read Article**
    - Frazat: "read article", "open article", "view article", "show article"
    - Përshkrimi: Hap artikullin e plotë

18. **Share Article**
    - Frazat: "share article", "share this article"
    - Përshkrimi: Shpërnda artikull

---

## 🎯 Event Interactions

19. **Register for Event**
    - Frazat: "register for event", "sign up for event", "join event", "register"
    - Përshkrimi: Regjistrohu për event (kërkon modal ose navigim)

20. **View Event Details**
    - Frazat: "show event details", "event details", "more info", "event info"
    - Përshkrimi: Shfaq detajet e eventit

---

## 🔍 Search & Discovery

21. **Open Search**
    - Frazat: "search", "open search", "focus search", "show search box"
    - Përshkrimi: Fokus në search box

22. **Search for Content**
    - Frazat: "search for [query]", "find [query]", "look for [query]"
    - Përshkrimi: Kërko për përmbajtje specifike (kërkon parsing të query)

23. **Clear Search**
    - Frazat: "clear search", "reset search", "clear"
    - Përshkrimi: Pastro search box

---

## 📜 Scroll & Navigation

24. **Scroll Down**
    - Frazat: "scroll down", "scroll down page", "go down", "move down", "page down"
    - Status: ✅ Funksionon

25. **Scroll Up**
    - Frazat: "scroll up", "scroll up page", "go up", "move up", "page up"
    - Status: ✅ Funksionon

26. **Scroll to Top**
    - Frazat: "scroll to top", "go to top", "top of page", "beginning"
    - Përshkrimi: Shko në fillim të faqes

27. **Scroll to Bottom**
    - Frazat: "scroll to bottom", "go to bottom", "end of page"
    - Përshkrimi: Shko në fund të faqes

---

## 🎛️ UI Controls

28. **Close Modal/Dialog**
    - Frazat: "close", "close modal", "dismiss", "cancel"
    - Përshkrimi: Mbyll modal ose dialog

29. **Open Menu**
    - Frazat: "open menu", "show menu", "menu"
    - Përshkrimi: Hap menunë mobile

30. **Close Menu**
    - Frazat: "close menu", "hide menu"
    - Përshkrimi: Mbyll menunë mobile

31. **Toggle Theme** (nëse ka dark mode)
    - Frazat: "toggle theme", "dark mode", "light mode", "switch theme"
    - Përshkrimi: Ndrysho temën

---

## 📧 Subscription & Notifications

32. **Subscribe**
    - Frazat: "subscribe", "sign up", "subscribe to updates", "get notifications"
    - Përshkrimi: Abonohu për njoftime

33. **Unsubscribe**
    - Frazat: "unsubscribe", "stop notifications", "cancel subscription"
    - Përshkrimi: Çabonohu nga njoftimet

---

## 🔐 Account Management

34. **Logout**
    - Frazat: "logout", "log out", "sign out", "exit account"
    - Përshkrimi: Dil nga llogaria

35. **View Profile** (nëse ka profile page)
    - Frazat: "view profile", "my profile", "profile", "account"
    - Përshkrimi: Shfaq profilin e përdoruesit

---

## 📱 Responsive Actions

36. **Back**
    - Frazat: "go back", "back", "previous page", "return"
    - Përshkrimi: Kthehu në faqen e mëparshme

37. **Forward**
    - Frazat: "go forward", "forward", "next page"
    - Përshkrimi: Shko përpara në historinë e browser-it

38. **Refresh Page**
    - Frazat: "refresh", "reload", "refresh page", "reload page"
    - Përshkrimi: Rifresko faqen

---

## 🎨 Content Actions

39. **Expand Content**
    - Frazat: "expand", "show more", "read more", "see more"
    - Përshkrimi: Zgjeroni përmbajtjen e shkurtuar

40. **Collapse Content**
    - Frazat: "collapse", "show less", "hide", "minimize"
    - Përshkrimi: Mbyll përmbajtjen e zgjeruar

---

## 📊 Filter & Sort

41. **Filter Events**
    - Frazat: "filter events", "show filters", "filter by category"
    - Përshkrimi: Hap filter options për events

42. **Sort Content**
    - Frazat: "sort by date", "sort by popularity", "sort by name"
    - Përshkrimi: Rendit përmbajtjen

---

## 🌐 Language & Locale

43. **Change Language** (nëse ka multi-language support)
    - Frazat: "change language", "switch language", "english", "albanian"
    - Përshkrimi: Ndrysho gjuhën e platformës

---

## 📝 Form Actions

44. **Submit Form**
    - Frazat: "submit", "send", "submit form", "send form"
    - Përshkrimi: Dërgo formularin aktive

45. **Clear Form**
    - Frazat: "clear form", "reset form", "clear all"
    - Përshkrimi: Pastro të gjitha fushat e formularit

---

## 🎯 Quick Actions

46. **Go to Top**
    - Frazat: "go to top", "top", "scroll to top", "beginning"
    - Përshkrimi: Shko në fillim të faqes

47. **Go to Bottom**
    - Frazat: "go to bottom", "bottom", "scroll to bottom", "end"
    - Përshkrimi: Shko në fund të faqes

---

## 📋 Kategoritë e Komandave

### Navigation Commands
- Navigim midis faqeve
- Scroll dhe pozicionim
- Back/Forward në historinë e browser-it

### Interaction Commands
- Like, Comment, Share
- Play/Pause për video
- Expand/Collapse për përmbajtje

### Search Commands
- Hap/Close search
- Kërko për përmbajtje
- Clear search

### Account Commands
- Sign In/Out
- Register
- Profile management

### Content Commands
- Read/View artikuj
- Filter dhe Sort
- Expand/Collapse

---

## 🔄 Priority Implementation

### Priority 1 (Kritike - Tani)
1. ✅ Scroll Down/Up (tashmë funksionon)
2. ⚠️ Navigim në faqe (Home, Events, News, About, FAQ, Contact)
3. ⚠️ Search focus
4. ⚠️ Sign In/Sign Up navigation

### Priority 2 (E rëndësishme - Së shpejti)
5. Like/Share për video dhe artikuj
6. Scroll to Top/Bottom
7. Back/Forward navigation
8. Close modal/menu

### Priority 3 (Nice to have)
9. Comment actions
10. Filter/Sort
11. Form submission
12. Theme toggle

---

## 📝 Shënime për Implementim

1. **Navigim**: Përdor Vue Router (`router.push()`) në vend të `window.location.href` për navigim më të shpejtë
2. **Frazat**: Shto më shumë variacione të frazave për çdo komandë
3. **Context Awareness**: Komandat duhet të jenë kontekstual (p.sh. "like this" vetëm kur jemi në video/article)
4. **Feedback**: Jep feedback audio ose visual kur komanda ekzekutohet
5. **Error Handling**: Trajto rastet kur komanda nuk mund të ekzekutohet

---

**Dokumenti i krijuar:** 2025-01-29
**Status:** 📋 Lista e plotë e veçorive për integrim

