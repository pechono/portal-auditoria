<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RepositorioArchivosCaso5Seeder extends Seeder
{
    public function run(): void
    {
        $casoId = DB::table('casos')->where('codigo', 'HIPERVIEW')->value('id');

        if (!$casoId) {
            $casoId = DB::table('casos')->insertGetId([
                'codigo'      => 'HIPERVIEW',
                'nombre'      => 'HiperView + Net S.R.L.',
                'descripcion' => 'Proveedor de televisión por cable e internet de Chamical, La Rioja. Sistema EventGES, módulo de facturación y servicios premium.',
                'activo'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
        $now = Carbon::now();

        $archivos = [

            // ── DOCUMENTOS INSTITUCIONALES ──────────────────────────────────
            [
                'nombre'          => 'Reseña Institucional - HiperView + Net S.R.L.',
                'nombre_original' => 'hiperview_DOC01_Resena.docx',
                'path'            => 'repositorio/hiperview_DOC01_Resena.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Políticas y Procedimientos Internos - HiperView + Net S.R.L.',
                'nombre_original' => 'hiperview_DOC02_Politicas.docx',
                'path'            => 'repositorio/hiperview_DOC02_Politicas.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Eventos y Recaudación - Período 2020-2021',
                'nombre_original' => 'hiperview_DOC03_EventosRecaudacion.docx',
                'path'            => 'repositorio/hiperview_DOC03_EventosRecaudacion.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Registro de Participantes y Accesos - Período 2020-2021',
                'nombre_original' => 'hiperview_DOC04_RegistroParticipantes.docx',
                'path'            => 'repositorio/hiperview_DOC04_RegistroParticipantes.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Manual Técnico - Sistema EventGES',
                'nombre_original' => 'hiperview_DOC05_ManualTecnico.docx',
                'path'            => 'repositorio/hiperview_DOC05_ManualTecnico.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Informe Técnico de Relevamiento Post-Tormenta',
                'nombre_original' => 'hiperview_DOC06_InformePostTormenta.docx',
                'path'            => 'repositorio/hiperview_DOC06_InformePostTormenta.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // ── ENTREVISTAS ──────────────────────────────────────────────────
            [
                'nombre'          => 'Entrevista - Sr. Rubén Fernández - Propietario y Gerente General',
                'nombre_original' => 'hiperview_HV-E01_Ruben_Fernandez.docx',
                'path'            => 'repositorio/hiperview_HV-E01_Ruben_Fernandez.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sra. Delia Encarnación Castro - Empleada de Facturación',
                'nombre_original' => 'hiperview_HV-E02_Delia_Castro.docx',
                'path'            => 'repositorio/hiperview_HV-E02_Delia_Castro.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Daniel García - Instalador de Fibra Óptica (ex Encargado de Sistemas)',
                'nombre_original' => 'hiperview_HV-E03_Daniel_Garcia.docx',
                'path'            => 'repositorio/hiperview_HV-E03_Daniel_Garcia.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Miguel Palacios - Encargado de Sistemas y Redes',
                'nombre_original' => 'hiperview_HV-E04_Miguel_Palacios.docx',
                'path'            => 'repositorio/hiperview_HV-E04_Miguel_Palacios.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Daniel Vera - Administrativo / Atención al Cliente',
                'nombre_original' => 'hiperview_HV-E05_Daniel_Vera.docx',
                'path'            => 'repositorio/hiperview_HV-E05_Daniel_Vera.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sra. Vanesa Romero - Administrativa / Atención al Cliente',
                'nombre_original' => 'hiperview_HV-E06_Vanesa_Romero.docx',
                'path'            => 'repositorio/hiperview_HV-E06_Vanesa_Romero.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Cdor. Ezequiel Bazán - Contador',
                'nombre_original' => 'hiperview_HV-E07_Ezequiel_Bazan.docx',
                'path'            => 'repositorio/hiperview_HV-E07_Ezequiel_Bazan.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Walter Cisneros - Encargado de Ventas de Internet',
                'nombre_original' => 'hiperview_HV-E08_Walter_Cisneros.docx',
                'path'            => 'repositorio/hiperview_HV-E08_Walter_Cisneros.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Bruno Achával - Instalador de Fibra Óptica',
                'nombre_original' => 'hiperview_HV-E09_Bruno_Achaval.docx',
                'path'            => 'repositorio/hiperview_HV-E09_Bruno_Achaval.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Hugo Maldonado - Supervisor Técnico de Telecomunicaciones',
                'nombre_original' => 'hiperview_HV-E10_Hugo_Maldonado.docx',
                'path'            => 'repositorio/hiperview_HV-E10_Hugo_Maldonado.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Srta. Yamila Torrejón - Oficinista',
                'nombre_original' => 'hiperview_HV-E11_Yamila_Torrejon.docx',
                'path'            => 'repositorio/hiperview_HV-E11_Yamila_Torrejon.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Cristian Ávalos - Oficinista',
                'nombre_original' => 'hiperview_HV-E12_Cristian_Avalos.docx',
                'path'            => 'repositorio/hiperview_HV-E12_Cristian_Avalos.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        DB::table('repositorio_archivos')->insert($archivos);

        $this->command->info('Caso 5 - HiperView + Net S.R.L.: ' . count($archivos) . ' archivos registrados.');
    }
}
