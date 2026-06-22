<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RepositorioArchivosCaso4Seeder extends Seeder
{
    public function run(): void
    {
        $casoId = DB::table('casos')->where('codigo', 'CALIVA')->value('id');

        if (!$casoId) {
            $casoId = DB::table('casos')->insertGetId([
                'codigo'      => 'CALIVA',
                'nombre'      => 'Taller Mecánico Multimarca Caliva S.R.L.',
                'descripcion' => 'Taller mecánico multimarca de Chamical, La Rioja. Sistema de gestión TallerGES, módulo de stock de repuestos.',
                'activo'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
        $now = Carbon::now();

        $archivos = [

            // ── DOCUMENTOS INSTITUCIONALES ──────────────────────────────────
            [
                'nombre'          => 'Reseña Institucional - Caliva S.R.L.',
                'nombre_original' => 'caliva_DOC01_Resena.docx',
                'path'            => 'repositorio/caliva_DOC01_Resena.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Políticas y Procedimientos Internos - Caliva S.R.L.',
                'nombre_original' => 'caliva_DOC02_Politicas.docx',
                'path'            => 'repositorio/caliva_DOC02_Politicas.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Stock y Movimientos de Inventario - Ejercicio 2024',
                'nombre_original' => 'caliva_DOC03_Stock.docx',
                'path'            => 'repositorio/caliva_DOC03_Stock.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Órdenes de Trabajo - Ejercicio 2024',
                'nombre_original' => 'caliva_DOC04_OrdenesTrabajo.docx',
                'path'            => 'repositorio/caliva_DOC04_OrdenesTrabajo.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Manual Técnico - Módulo de Stock TallerGES',
                'nombre_original' => 'caliva_DOC05_ManualTecnico.docx',
                'path'            => 'repositorio/caliva_DOC05_ManualTecnico.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Consulta Formal de Distribuidora Albornoz - Caliva S.R.L.',
                'nombre_original' => 'caliva_DOC06_Albornoz.docx',
                'path'            => 'repositorio/caliva_DOC06_Albornoz.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // ── ENTREVISTAS ──────────────────────────────────────────────────
            [
                'nombre'          => 'Entrevista - Sr. Juan Román García - Propietario',
                'nombre_original' => 'caliva_TM-E01_Juan_Roman_Garcia.docx',
                'path'            => 'repositorio/caliva_TM-E01_Juan_Roman_Garcia.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Gabriel Omar Batís - Jefe de Depósito de Repuestos',
                'nombre_original' => 'caliva_TM-E02_Gabriel_Omar_Batis.docx',
                'path'            => 'repositorio/caliva_TM-E02_Gabriel_Omar_Batis.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Martín Díaz - Mecánico, Turno Noche',
                'nombre_original' => 'caliva_TM-E03_Martin_Diaz.docx',
                'path'            => 'repositorio/caliva_TM-E03_Martin_Diaz.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Franco Mercado - Mecánico, Turno Mañana/Tarde',
                'nombre_original' => 'caliva_TM-E04_Franco_Mercado.docx',
                'path'            => 'repositorio/caliva_TM-E04_Franco_Mercado.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Franco Rodrigo Nieva - Administrativo / Facturación',
                'nombre_original' => 'caliva_TM-E05_Franco_Rodrigo_Nieva.docx',
                'path'            => 'repositorio/caliva_TM-E05_Franco_Rodrigo_Nieva.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Santiago Scott - Mecánico',
                'nombre_original' => 'caliva_TM-E06_Santiago_Scott.docx',
                'path'            => 'repositorio/caliva_TM-E06_Santiago_Scott.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        DB::table('repositorio_archivos')->insert($archivos);

        $this->command->info('Caso 4 - Taller Mecánico Multimarca Caliva S.R.L.: ' . count($archivos) . ' archivos registrados.');
    }
}
