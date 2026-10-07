<?php
/**
 * Script to add taxonomy terms for Data Talks Hub
 * Run this once via WP-CLI or add to functions.php temporarily
 * 
 * Usage via WP-CLI:
 * wp eval-file add-taxonomy-terms.php
 * 
 * OR add to functions.php and reload admin page once
 */

// Terminy taksonomii - struktura: 'taxonomy' => [terminy]
$taxonomy_terms = [
    'sciezka' => [
        [
            'name' => 'Data Governance & Data Quality',
            'slug' => 'data-governance-quality',
            'description' => 'Zarządzanie danymi i jakość danych'
        ],
        [
            'name' => 'AI inside Data Architecture',
            'slug' => 'ai-data-architecture',
            'description' => 'AI wewnątrz architektury danych'
        ],
        [
            'name' => 'Machine Learning w produkcji',
            'slug' => 'ml-produkcji',
            'description' => 'Wdrażanie i utrzymanie modeli ML'
        ],
        [
            'name' => 'Modern Data Management',
            'slug' => 'modern-data-management',
            'description' => 'Nowoczesne podejścia do zarządzania danymi'
        ],
        [
            'name' => 'Agenci AI i modele dużych zbiorów algorytmów',
            'slug' => 'ai-agents-llm',
            'description' => 'Agenci AI i duże modele językowe'
        ],
        [
            'name' => 'Data Mesh, Data Products & Data Marketplace',
            'slug' => 'data-mesh-products',
            'description' => 'Architektura Data Mesh i produkty danych'
        ],
        [
            'name' => 'Analityka predykcyjna',
            'slug' => 'predictive-analytics',
            'description' => 'Analityka predykcyjna i forecasting'
        ],
        [
            'name' => 'Regulacje compliance (GDPR, DPIA)',
            'slug' => 'compliance-gdpr',
            'description' => 'Compliance i regulacje (GDPR, DPIA)'
        ],
        [
            'name' => 'Bezpieczeństwo i prywatność danych',
            'slug' => 'security-privacy',
            'description' => 'Bezpieczeństwo danych i prywatność'
        ],
        [
            'name' => 'Business Intelligence nową generacją',
            'slug' => 'bi-next-gen',
            'description' => 'BI nowej generacji'
        ],
        [
            'name' => 'Zarządzanie danymi i budowy ekosystemów organizacji',
            'slug' => 'data-ecosystem',
            'description' => 'Budowanie ekosystemów danych w organizacji'
        ]
    ],
    'format' => [
        [
            'name' => 'Keynote',
            'slug' => 'keynote',
            'description' => 'Prelekcja otwierająca/zamykająca'
        ],
        [
            'name' => 'Case Study',
            'slug' => 'case-study',
            'description' => 'Studium przypadku z praktyki'
        ],
        [
            'name' => 'Debata oksfordzka',
            'slug' => 'debata-oksfordzka',
            'description' => 'Debata ze sprzeciwem'
        ],
        [
            'name' => 'Panel',
            'slug' => 'panel',
            'description' => 'Dyskusja panelowa z wieloma prelegentami'
        ],
        [
            'name' => 'Warsztat',
            'slug' => 'warsztat',
            'description' => 'Warsztat interaktywny'
        ]
    ],
    'jezyk' => [
        [
            'name' => 'Polski',
            'slug' => 'pl',
            'description' => 'Język polski'
        ],
        [
            'name' => 'English',
            'slug' => 'en',
            'description' => 'English language'
        ]
    ]
];

// Dodaj terminy
$count = 0;
foreach ( $taxonomy_terms as $taxonomy => $terms ) {
    foreach ( $terms as $term ) {
        $result = wp_insert_term(
            $term['name'],
            $taxonomy,
            [
                'slug' => $term['slug'],
                'description' => $term['description']
            ]
        );

        if ( is_wp_error( $result ) ) {
            echo "❌ Błąd przy dodawaniu '{$term['name']}' do {$taxonomy}: " . $result->get_error_message() . "\n";
        } else {
            echo "✅ Dodano '{$term['name']}' do {$taxonomy}\n";
            $count++;
        }
    }
}

echo "\n🎉 Dodano {$count} terminów!\n";
