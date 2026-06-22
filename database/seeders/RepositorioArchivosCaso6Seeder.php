<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RepositorioArchivosCaso6Seeder extends Seeder
{
    public function run(): void
    {
        $casoId = DB::table('casos')->where('codigo', 'ALBORNOZ')->value('id');

        if (!$casoId) {
            $casoId = DB::table('casos')->insertGetId([
                'codigo'      => 'ALBORNOZ',
                'nombre'      => 'Distribuidora de Repuestos de Alto Valor Albornoz S.A.',
                'descripcion' => 'Importadora de autopartes de alto valor con sede en Buenos Aires y depósitos regionales en Tucumán y Mendoza. Sistema GunterIX 3.1, módulos de stock, logística y flota.',
                'activo'      => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
        $now = Carbon::now();

        $archivos = [

            // ── DOCUMENTOS INSTITUCIONALES ──────────────────────────────────
            [
                'nombre'          => 'Reseña Institucional - Distribuidora Albornoz',
                'nombre_original' => 'albornoz_DOC01_Resena.docx',
                'path'            => 'repositorio/albornoz_DOC01_Resena.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Políticas y Procedimientos Internos - Distribuidora Albornoz',
                'nombre_original' => 'albornoz_DOC02_Politicas.docx',
                'path'            => 'repositorio/albornoz_DOC02_Politicas.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Stock - Depósito Mendoza',
                'nombre_original' => 'albornoz_DOC03_StockMendoza.docx',
                'path'            => 'repositorio/albornoz_DOC03_StockMendoza.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Recepción - Depósito Tucumán',
                'nombre_original' => 'albornoz_DOC04_RecepcionTucuman.docx',
                'path'            => 'repositorio/albornoz_DOC04_RecepcionTucuman.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Reporte de Combustible y Viáticos - Flota Propia',
                'nombre_original' => 'albornoz_DOC05_CombustibleViaticos.docx',
                'path'            => 'repositorio/albornoz_DOC05_CombustibleViaticos.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Tabla de Distancias Oficiales y Consumo Estándar por Ruta',
                'nombre_original' => 'albornoz_DOC06_DistanciasConsumo.docx',
                'path'            => 'repositorio/albornoz_DOC06_DistanciasConsumo.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Manual Técnico - Sistema GunterIX 3.1',
                'nombre_original' => 'albornoz_DOC07_ManualTecnico.docx',
                'path'            => 'repositorio/albornoz_DOC07_ManualTecnico.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Propuesta de Actualización a GunterIX 4.0',
                'nombre_original' => 'albornoz_DOC08_PropuestaGunterIX4.docx',
                'path'            => 'repositorio/albornoz_DOC08_PropuestaGunterIX4.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Comunicación con Casa Central - Reclamo de Devolución',
                'nombre_original' => 'albornoz_DOC09_ComunicacionAlemania.docx',
                'path'            => 'repositorio/albornoz_DOC09_ComunicacionAlemania.docx',
                'categoria'       => 'documento',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // ── ENTREVISTAS ──────────────────────────────────────────────────
            [
                'nombre'          => 'Entrevista - Sr. Aníbal Albornoz - Propietario y Gerente General',
                'nombre_original' => 'albornoz_AL-E01_Anibal_Albornoz.docx',
                'path'            => 'repositorio/albornoz_AL-E01_Anibal_Albornoz.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sra. Valeria Núñez - Gerente de Logística',
                'nombre_original' => 'albornoz_AL-E02_Valeria_Nunez.docx',
                'path'            => 'repositorio/albornoz_AL-E02_Valeria_Nunez.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Matías Lescano - Encargado de Depósito Buenos Aires',
                'nombre_original' => 'albornoz_AL-E03_Matias_Lescano.docx',
                'path'            => 'repositorio/albornoz_AL-E03_Matias_Lescano.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Joaquín Espeche - Encargado de Depósito Tucumán',
                'nombre_original' => 'albornoz_AL-E04_Joaquin_Espeche.docx',
                'path'            => 'repositorio/albornoz_AL-E04_Joaquin_Espeche.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Damián Quiroga - Encargado de Depósito Mendoza',
                'nombre_original' => 'albornoz_AL-E05_Damian_Quiroga.docx',
                'path'            => 'repositorio/albornoz_AL-E05_Damian_Quiroga.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Ramón Sosa - Chofer (Ruta Buenos Aires - Mendoza)',
                'nombre_original' => 'albornoz_AL-E06_Ramon_Sosa.docx',
                'path'            => 'repositorio/albornoz_AL-E06_Ramon_Sosa.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Leonel Funes - Chofer (Ruta Buenos Aires - Tucumán / NOA)',
                'nombre_original' => 'albornoz_AL-E07_Leonel_Funes.docx',
                'path'            => 'repositorio/albornoz_AL-E07_Leonel_Funes.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Adrián Coronel - Chofer (Ruta Tucumán - Mendoza)',
                'nombre_original' => 'albornoz_AL-E08_Adrian_Coronel.docx',
                'path'            => 'repositorio/albornoz_AL-E08_Adrian_Coronel.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Cra. Mariana Sotelo - Contadora',
                'nombre_original' => 'albornoz_AL-E09_Mariana_Sotelo.docx',
                'path'            => 'repositorio/albornoz_AL-E09_Mariana_Sotelo.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Srta. Florencia Aguirre - Administrativa',
                'nombre_original' => 'albornoz_AL-E10_Florencia_Aguirre.docx',
                'path'            => 'repositorio/albornoz_AL-E10_Florencia_Aguirre.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Norberto Cabral - Empleado de Depósito Buenos Aires',
                'nombre_original' => 'albornoz_AL-E11_Norberto_Cabral.docx',
                'path'            => 'repositorio/albornoz_AL-E11_Norberto_Cabral.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Walter Gimenez - Mecánico de Flota',
                'nombre_original' => 'albornoz_AL-E12_Walter_Gimenez.docx',
                'path'            => 'repositorio/albornoz_AL-E12_Walter_Gimenez.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sr. Hernán Paz - Vendedor Comercial',
                'nombre_original' => 'albornoz_AL-E13_Hernan_Paz.docx',
                'path'            => 'repositorio/albornoz_AL-E13_Hernan_Paz.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'nombre'          => 'Entrevista - Sra. Liliana Bértola - Recursos Humanos',
                'nombre_original' => 'albornoz_AL-E14_Liliana_Bertola.docx',
                'path'            => 'repositorio/albornoz_AL-E14_Liliana_Bertola.docx',
                'categoria'       => 'entrevista',
                'caso_id'         => $casoId,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        DB::table('repositorio_archivos')->insert($archivos);

        $this->command->info('Caso 6 - Distribuidora de Repuestos de Alto Valor Albornoz S.A.: ' . count($archivos) . ' archivos registrados.');
    }
}
