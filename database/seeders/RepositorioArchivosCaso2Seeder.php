<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RepositorioArchivosCaso2Seeder extends Seeder
{
    public function run(): void
    {
        $casoId = DB::table('casos')->where('codigo', 'CSIL')->value('id');

        if (!$casoId) {
            $casoId = DB::table('casos')->insertGetId([
                'codigo'      => 'CSIL',
                'nombre'      => 'Centro de Salud Integral de los Llanos S.R.L.',
                'descripcion' => 'Centro de salud privado de La Rioja. Sistema de liquidación de honorarios médicos SaludGest.',
                'activo'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
        $now    = Carbon::now();

        $archivos = [

            // ── CONSIGNA DEL TRABAJO PRACTICO ──────────────────────────────
            [
                'nombre'          => 'Trabajo Practico - Consigna Caso 2',
                'nombre_original' => 'csil_CASO02_Consigna.docx',
                'path'            => 'repositorio/csil_CASO02_Consigna.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // ── DOCUMENTOS INSTITUCIONALES ──────────────────────────────────
            [
                'nombre'          => 'Resena Institucional - CSIL S.R.L.',
                'nombre_original' => 'csil_DOC01_Resena.docx',
                'path'            => 'repositorio/csil_DOC01_Resena.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Manual de Politicas y Procedimientos Vigentes',
                'nombre_original' => 'csil_DOC02_Politicas.docx',
                'path'            => 'repositorio/csil_DOC02_Politicas.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Facturacion Trimestral',
                'nombre_original' => 'csil_DOC03_Facturacion.docx',
                'path'            => 'repositorio/csil_DOC03_Facturacion.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Liquidaciones del Sistema SaludGest',
                'nombre_original' => 'csil_DOC04_Liquidaciones.docx',
                'path'            => 'repositorio/csil_DOC04_Liquidaciones.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Manual Tecnico - Modulo de Liquidacion SaludGest',
                'nombre_original' => 'csil_DOC05_ManualTecnico.docx',
                'path'            => 'repositorio/csil_DOC05_ManualTecnico.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Informe de Auditoria Operativa 2021',
                'nombre_original' => 'csil_DOC06_Auditoria2021.docx',
                'path'            => 'repositorio/csil_DOC06_Auditoria2021.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // ── ENTREVISTAS ──────────────────────────────────────────────────
            [
                'nombre'          => 'Entrevista - Dra. Carmen Valdes - Directora Medica y Socia Gerente',
                'nombre_original' => 'csil_CE-01_Dra_Carmen_Valdes.docx',
                'path'            => 'repositorio/csil_CE-01_Dra_Carmen_Valdes.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Pablo Rios - Administrador General',
                'nombre_original' => 'csil_CE-02_Sr_Pablo_Rios.docx',
                'path'            => 'repositorio/csil_CE-02_Sr_Pablo_Rios.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sra. Lucia Ferrer - Contadora',
                'nombre_original' => 'csil_CE-03_Sra_Lucia_Ferrer.docx',
                'path'            => 'repositorio/csil_CE-03_Sra_Lucia_Ferrer.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Martin Ochoa - Jefe de Sistemas e Informatica',
                'nombre_original' => 'csil_CE-04_Sr_Martin_Ochoa.docx',
                'path'            => 'repositorio/csil_CE-04_Sr_Martin_Ochoa.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Dr. Esteban Ruiz - Medico Clinico',
                'nombre_original' => 'csil_CE-05_Dr_Esteban_Ruiz.docx',
                'path'            => 'repositorio/csil_CE-05_Dr_Esteban_Ruiz.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Dra. Ana Pereyra - Medica Pediatra',
                'nombre_original' => 'csil_CE-06_Dra_Ana_Pereyra.docx',
                'path'            => 'repositorio/csil_CE-06_Dra_Ana_Pereyra.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sra. Rosa Mamani - Recepcionista',
                'nombre_original' => 'csil_CE-07_Sra_Rosa_Mamani.docx',
                'path'            => 'repositorio/csil_CE-07_Sra_Rosa_Mamani.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Jorge Ibanez - Tecnico de Laboratorio',
                'nombre_original' => 'csil_CE-08_Sr_Jorge_Ibanez.docx',
                'path'            => 'repositorio/csil_CE-08_Sr_Jorge_Ibanez.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sra. Patricia Suarez - Facturadora de Obras Sociales',
                'nombre_original' => 'csil_CE-09_Sra_Patricia_Suarez.docx',
                'path'            => 'repositorio/csil_CE-09_Sra_Patricia_Suarez.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Nicolas Leiva - Tecnico de Soporte IT',
                'nombre_original' => 'csil_CE-10_Sr_Nicolas_Leiva.docx',
                'path'            => 'repositorio/csil_CE-10_Sr_Nicolas_Leiva.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        DB::table('repositorio_archivos')->insert($archivos);

        $this->command->info('Caso 2 - Centro de Salud Integral de los Llanos S.R.L. (CSIL): ' . count($archivos) . ' archivos registrados.');
    }
}
