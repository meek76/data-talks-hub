# Data Talks Hub – WordPress Configuration

Konfiguracja WordPress + ACF Pro dla portalu Data Talks Hub.

## Zawartość

- **acf-export.json** – Pełna konfiguracja ACF Pro
  - Custom Post Types: `material`, `prelegent`
  - Taksonomie: `sciezka`, `format`, `jezyk` (tylko dla `material`)
  - Grupy pól ACF
  - Terminy taksonomii

## Instalacja

### 1. Import do ACF Pro

1. W panelu WordPress: **ACF Pro → Tools → Import**
2. Wklej zawartość `acf-export.json`
3. Potwierdź – ACF automatycznie stworzy:
   - Custom Post Types
   - Taksonomie
   - Grupy pól
   - Terminy

### 2. Konfiguracja Vimeo

#### Teaser (publiczny, 80 s)
- Ustaw film jako **publiczny** albo **unlisted**
- Domeny: bez ograniczeń
- Skopiuj ID z URL: `https://vimeo.com/123456789`
- Wstaw do pola: `teaser_vimeo_id`

#### Pełne wideo (chronione)
- Film: **Privacy → Only me**
- Embed: **Specific domains** (wpisz swoją domenę, np. `datalks.example.com`)
- Skopiuj ID i wstaw do: `pelne_vimeo_id`
- Opcjonalnie: jeśli Vimeo wymaga, dodaj `pelne_vimeo_hash`

### 3. Konfiguracja Ivory Search

1. **Ivory Search → Add New**
   - Nazwa: „Materiały"
   - Slug: `materialy`

2. **Includes:**
   - Post Types: `material`
   - Custom Fields: `lead`, `artykul`, `transkrypcja`
   - Taxonomies: `sciezka`, `format`, `jezyk`

3. **Excludes:**
   - Nie dodawaj pól: `teaser_vimeo_id`, `pelne_vimeo_id` (nie przeszukuj URL-ów)

4. **Opcje:**
   - Włącz *AJAX Search* (jeśli Pro)
   - Wyłącz *Search on every page*

### 4. Konfiguracja Breakdance

#### Szablony

**Single Material:**
- Kolumna lewa: wideo (teaser/pełne), tytuł, lead, artykuł, transkrypcja (accordion)
- Kolumna prawa: prelegenci (cards), formularz (dla gości), newsletter

**Single Prelegent:**
- Avatar, imię, stanowisko, firma, bio
- Post Loop: materiały tego prelegenta

**Archiwum Materiałów:**
- Pasek filtrów (chipy: ścieżka, format, język)
- Post Loop: karty materiałów
- Licznik: „Znaleziono X z Y · łącznie HH:MM"

#### Dynamic Data

Wszędy gdzie trzeba danych z ACF, użyj:
- `{acf field_name}` – dla pól zwykłych
- `{acf field_name key="label"}` – dla taksonomii
- `{post_title}`, `{post_excerpt}` – dla pól posta

#### Warunki widoczności

Dla elementu z `pelne_vimeo_id`:
- **Conditions → User Logged In** ✓

Dla teaser:
- Brak warunku (widoczne dla wszystkich)

#### Post Loop (Archiwum)

```
Post Type: material
Posts per page: -1 (pokaż wszystkie)
Order by: meta_value_num / wyswietlenia (DESC)
```

Opcjonalnie sortuj po dacie: `post_date DESC`

### 5. AJAX Filtrowanie (client-side)

Dodaj plik JS do Breakdance lub do `functions.php`:

```javascript
// filters.js
document.addEventListener('DOMContentLoaded', () => {
  const cards = [...document.querySelectorAll('.js-card')];
  const state = { sciezka: 'all', format: 'all', jezyk: 'all' };

  const toSec = t => t.split(':').reduce((a, v) => a * 60 + +v, 0);
  const fmt = s => `${Math.floor(s/60)}:${String(s%60).padStart(2,'0')}`;

  function apply() {
    let shown = 0, total = 0;
    cards.forEach(c => {
      const ok = ['sciezka','format','jezyk'].every(k =>
        state[k] === 'all' || c.dataset[k].split(',').includes(state[k]));
      c.hidden = !ok;
      if (ok) { shown++; total += toSec(c.dataset.duration); }
    });
    document.querySelector('.js-count').textContent =
      `Znaleziono ${shown} z ${cards.length} materiałów · łącznie ${fmt(total)}`;
  }

  document.querySelectorAll('[data-filter-group]').forEach(btn =>
    btn.addEventListener('click', () => {
      state[btn.dataset.filterGroup] = btn.dataset.filterValue;
      document.querySelectorAll(`[data-filter-group="${btn.dataset.filterGroup}"]`)
        .forEach(b => b.classList.toggle('is-active', b === btn));
      apply();
    }));
  apply();
});
```

## Struktura pól ACF

### Prelegent (CPT: `prelegent`)

| Pole | Typ | Opis |
|---|---|---|
| `stanowisko` | Text | np. Solution Architect |
| `firma` | Text | np. Mendix |
| `bio` | Textarea | Biogram prelegenta |
| `zdjecie` | Image | Zdjęcie profilowe |
| `materialy` | Relationship | Materiały (dwukierunkowa) |

### Materiał (CPT: `material`)

| Pole | Typ | Opis |
|---|---|---|
| `lead` | Textarea | Podtytuł materiału |
| `prelegenci` | Relationship | Powiązani prelegenci (dwukierunkowa) |
| `czas_trwania` | Text | Format MM:SS |
| `wyswietlenia` | Number | Licznik wyświetleń |
| `teaser_vimeo_id` | Text | ID teaser (publiczny) |
| `pelne_vimeo_id` | Text | ID pełnego wideo (chronione) |
| `pelne_vimeo_hash` | Text | Hash Vimeo (opcjonalnie) |
| `gated` | True/False | Pełne tylko dla zalogowanych |
| `z_archiwum` | True/False | Badge IDT'25 |
| `sciezka` | Taxonomy | Ścieżka tematyczna (multi) |
| `format` | Taxonomy | Format (single) |
| `jezyk` | Taxonomy | Język: PL, EN |
| `artykul` | WYSIWYG | Artykuł towarzyszący |
| `transkrypcja` | WYSIWYG | Pełna transkrypcja |

## Taksonomie

### `sciezka` (hierarchiczna)

- Data Governance & Data Quality
- AI inside Data Architecture
- Machine Learning w produkcji
- Modern Data Management
- Agenci AI i modele dużych zbiorów algorytmów
- Data Mesh, Data Products & Data Marketplace
- Analityka predykcyjna
- Regulacje compliance (GDPR, DPIA)
- Bezpieczeństwo i prywatność danych
- Business Intelligence nową generacją
- Zarządzanie danymi i budowy ekosystemów organizacji

### `format` (płaska)

- Keynote
- Case Study
- Debata oksfordzka
- Panel
- Warsztat

### `jezyk` (płaska)

- Polski (pl)
- English (en)

## Bezpieczeństwo

- **Teaser:** publiczny, widoczny dla wszystkich
- **Pełne wideo:** tylko dla zalogowanych (warunek w Breakdance)
- **Vimeo privacy:** domain-level restrictions + unlisted
- **Ivory Search:** nie indeksuje URL-ów filmów

## Notatki

- Relacja prelegent ↔ materiały jest **dwukierunkowa** (ACF Pro)
- Przy 20-30 materiałach, filtry mogą być client-side (JavaScript)
- Licznik wyświetleń zwykle aktualizuje się przez AJAX/REST
- Transkrypcje są przeszukiwane przez Ivory Search

## Wsparcie

W razie pytań, sprawdź dokumentację:
- [ACF Pro Docs](https://www.advancedcustomfields.com/resources/)
- [Breakdance Docs](https://www.breakdance.com/docs/)
- [Vimeo API](https://developer.vimeo.com/)
- [Ivory Search](https://www.ivorysearch.com/)
