<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RepositorioArchivosCaso3Seeder extends Seeder
{
    public function run(): void
    {
        $casoId = 2; // Distribuidora Norte Grande S.R.L.
        $now    = Carbon::now();

        $archivos = [

            // ── DOCUMENTOS INSTITUCIONALES ─────────────────────────────────
            [
                'nombre'          => 'Reseña Institucional y Estructura Organizacional',
                'nombre_original' => 'dng_DOC01_Resena.docx',
                'path'            => 'repositorio/dng_DOC01_Resena.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Manual de Políticas y Procedimientos Vigentes',
                'nombre_original' => 'dng_DOC02_Politicas.docx',
                'path'            => 'repositorio/dng_DOC02_Politicas.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Ventas y Caja — Primer Trimestre 2025',
                'nombre_original' => 'dng_DOC03_Ventas_Caja.docx',
                'path'            => 'repositorio/dng_DOC03_Ventas_Caja.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Stock y Movimientos de Depósito — Primer Trimestre 2025',
                'nombre_original' => 'dng_DOC04_Stock.docx',
                'path'            => 'repositorio/dng_DOC04_Stock.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Estado de Cuentas Corrientes y Análisis Contable — 1er Trimestre 2025',
                'nombre_original' => 'dng_DOC05_CuentasCorrientes.docx',
                'path'            => 'repositorio/dng_DOC05_CuentasCorrientes.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Informe de Auditoría Interna 2022 — Diferencias de Inventario',
                'nombre_original' => 'dng_DOC06_Auditoria2022.docx',
                'path'            => 'repositorio/dng_DOC06_Auditoria2022.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // ── ENTREVISTAS ────────────────────────────────────────────────
            [
                'nombre'          => 'Entrevista — Sr. Héctor Ramón Paz — Dueño y Gerente General',
                'nombre_original' => 'dng_DNG-E01_Sr_Héctor_Ramón_Paz.docx',
                'path'            => 'repositorio/dng_DNG-E01_Sr_Hector_Ramon_Paz.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista — Sr. Marcelo Quiroga — Jefe de Ventas',
                'nombre_original' => 'dng_DNG-E02_Sr_Marcelo_el_Toro_Quiroga.docx',
                'path'            => 'repositorio/dng_DNG-E02_Sr_Marcelo_el_Toro_Quiroga.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista — Sra. Roxana Medina — Cajera',
                'nombre_original' => 'dng_DNG-E03_Sra_Roxana_Medina.docx',
                'path'            => 'repositorio/dng_DNG-E03_Sra_Roxana_Medina.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista — Sr. Néstor Alderete — Encargado de Depósito',
                'nombre_original' => 'dng_DNG-E04_Sr_Néstor_el_Flaco_Alderete.docx',
                'path'            => 'repositorio/dng_DNG-E04_Sr_Nestor_el_Flaco_Alderete.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista — Sra. Graciela Romero — Repositora',
                'nombre_original' => 'dng_DNG-E05_Sra_Graciela_Romero.docx',
                'path'            => 'repositorio/dng_DNG-E05_Sra_Graciela_Romero.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista — Sr. Kevin Salinas — Vendedor Externo Zona Norte',
                'nombre_original' => 'dng_DNG-E06_Sr_Kevin_Salinas.docx',
                'path'            => 'repositorio/dng_DNG-E06_Sr_Kevin_Salinas.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista — Cra. Sandra Vega — Contadora Externa',
                'nombre_original' => 'dng_DNG-E07_Cra_Sandra_Vega.docx',
                'path'            => 'repositorio/dng_DNG-E07_Cra_Sandra_Vega.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // ── ORGANIGRAMA ────────────────────────────────────────────────
            [
                'nombre'          => 'Organigrama Institucional — Distribuidora Norte Grande S.R.L.',
                'nombre_original' => 'dng_organigrama.png',
                'path'            => 'repositorio/dng_organigrama.png',
                'categoria'       => 'otro',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Organigrama Institucional — Distribuidora Norte Grande S.R.L. (vectorial)',
                'nombre_original' => 'dng_organigrama.svg',
                'path'            => 'repositorio/dng_organigrama.svg',
                'categoria'       => 'otro',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        DB::table('repositorio_archivos')->insert($archivos);

        $this->command->info('✓ Caso 3 — Distribuidora Norte Grande S.R.L.: ' . count($archivos) . ' archivos registrados.');
    }
}

