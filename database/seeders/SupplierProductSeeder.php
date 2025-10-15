<?php

namespace Database\Seeders;

use App\Models\SupplierProduct;
use Illuminate\Database\Seeder;

class SupplierProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SupplierProduct::factory()->createMany([
            ['supplier_id' => 6, 'title' => 'ME FUNDA HOSH DESINFECTANTE Y NEUTRALIZADOR DE OLORES OCEANO AZUL', 'product_code' => 1234],
            ['supplier_id' => 6, 'title' => 'ME CONTRA ETIQUETA RUST REMOVER V GALON IMAGEN 2024', 'product_code' => 5678],
            ['supplier_id' => 6, 'title' => 'CAJA PALITOS RATTAN DUO Y TRIPACK CON SEPARADOR 20X28.5CM', 'product_code' => 9013],
            ['supplier_id' => 6, 'title' => 'Producto 3', 'product_code' => 9012],
            ['supplier_id' => 6, 'title' => 'Producto 4', 'product_code' => 3456],
            ['supplier_id' => 6, 'title' => 'Producto 5', 'product_code' => 7890],
            ['supplier_id' => 6, 'title' => 'Producto 6', 'product_code' => 2345],
            ['supplier_id' => 7, 'title' => 'Producto 7', 'product_code' => 678],
            ['supplier_id' => 7, 'title' => 'Producto 8', 'product_code' => 0123],
            ['supplier_id' => 7, 'title' => 'Producto 9', 'product_code' => 4567],
            ['supplier_id' => 7, 'title' => 'Producto 10', 'product_code' => 8901],
            ['supplier_id' => 7, 'title' => 'Producto 11', 'product_code' => 12345],
        ]);
    }
}
