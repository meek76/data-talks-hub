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
        'Wszystkie',
        'Zaufanie do danych',
        'Architektura pod AI',
        'MLOps i modele',
        'Jedna wersja prawdy',
        'Agenci AI',
        'Produkty danowe',
        'Prognozowanie',
        'AI Act & RODO',
        'DataOps',
        'BI + AI',
        'Kultura danych'
    ],
    'format' => [
        'Keynote',
        'Case Study',
        'Debata oksfordzka',
        'Panel',
        'Warsztat'
    ],
    'jezyk' => [
        'PL',
        'EN'
    ]
];

// Dodaj terminy
$count = 0;
foreach ( $taxonomy_terms as $taxonomy => $terms ) {
    foreach ( $terms as $term ) {
        $result = wp_insert_term(
            $term,
            $taxonomy
        );

        if ( is_wp_error( $result ) ) {
            echo "❌ Błąd przy dodawaniu '{$term}' do {$taxonomy}: " . $result->get_error_message() . "\n";
        } else {
            echo "✅ Dodano '{$term}' do {$taxonomy}\n";
            $count++;
        }
    }
}

echo "\n🎉 Dodano {$count} terminów!\n";
