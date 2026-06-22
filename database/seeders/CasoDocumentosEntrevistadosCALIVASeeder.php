<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caso;
use App\Models\Documento;
use App\Models\Entrevistado;
use App\Models\Etapa;

class CasoDocumentosEntrevistadosCALIVASeeder extends Seeder
{
    public function run(): void
    {
        $caso = Caso::firstOrCreate(
            ['codigo' => 'CALIVA'],
            [
                'nombre'      => 'Taller Mecánico Multimarca Caliva S.R.L.',
                'descripcion' => 'Taller mecánico multimarca de Chamical, La Rioja. Sistema de gestión TallerGES, módulo de stock de repuestos.',
                'activo'      => true,
            ]
        );

        if ($caso->documentos()->count() === 0) {
            $documentos = [
                ['codigo' => 'DOC-01', 'titulo' => 'Reseña Institucional - Caliva S.R.L.',                    'descripcion' => 'Historia y estructura del taller mecánico.'],
                ['codigo' => 'DOC-02', 'titulo' => 'Políticas y Procedimientos Internos - Caliva S.R.L.',     'descripcion' => 'Normativa interna vigente.'],
                ['codigo' => 'DOC-03', 'titulo' => 'Reporte de Stock y Movimientos de Inventario 2024',       'descripcion' => 'Movimientos del módulo de stock de repuestos del ejercicio 2024.'],
                ['codigo' => 'DOC-04', 'titulo' => 'Reporte de Órdenes de Trabajo 2024',                      'descripcion' => 'Órdenes de trabajo registradas en TallerGES durante 2024.'],
                ['codigo' => 'DOC-05', 'titulo' => 'Manual Técnico - Módulo de Stock TallerGES',              'descripcion' => 'Documentación técnica del módulo de stock del sistema TallerGES.'],
                ['codigo' => 'DOC-06', 'titulo' => 'Consulta Formal de Distribuidora Albornoz',               'descripcion' => 'Comunicación formal recibida de Distribuidora Albornoz relacionada al caso.'],
            ];

            foreach ($documentos as $doc) {
                Documento::create(array_merge($doc, ['caso_id' => $caso->id]));
            }
        }

        if ($caso->entrevistados()->count() === 0) {
            $entrevistados = [
                ['nombre' => 'Sr. Juan Román García',    'cargo' => 'Propietario',                          'area' => 'Gerencia',      'descripcion_rol' => 'Propietario del taller, quien solicita la auditoría.'],
                ['nombre' => 'Sr. Gabriel Omar Batís',   'cargo' => 'Jefe de Depósito de Repuestos',        'area' => 'Depósito',      'descripcion_rol' => 'Responsable del stock y movimiento de repuestos.'],
                ['nombre' => 'Sr. Martín Díaz',          'cargo' => 'Mecánico - Turno Noche',               'area' => 'Taller',        'descripcion_rol' => 'Mecánico del turno nocturno, retira repuestos del depósito.'],
                ['nombre' => 'Sr. Franco Mercado',       'cargo' => 'Mecánico - Turno Mañana/Tarde',        'area' => 'Taller',        'descripcion_rol' => 'Mecánico del turno diurno, retira repuestos del depósito.'],
                ['nombre' => 'Sr. Franco Rodrigo Nieva', 'cargo' => 'Administrativo / Facturación',         'area' => 'Administración','descripcion_rol' => 'Gestiona la facturación y documentación administrativa.'],
                ['nombre' => 'Sr. Santiago Scott',       'cargo' => 'Mecánico',                             'area' => 'Taller',        'descripcion_rol' => 'Mecánico de planta con observaciones sobre el sistema.'],
            ];

            foreach ($entrevistados as $e) {
                Entrevistado::create(array_merge($e, ['caso_id' => $caso->id]));
            }
        }

        if ($caso->etapas()->count() === 0) {
            $this->crearEtapas($caso->id);
        }

        $this->command->info('CALIVA: documentos, entrevistados y etapas vinculados.');
    }

    private function crearEtapas(int $casoId): void
    {
        $etapas = [
            ['numero' => 1, 'nombre' => 'Solicitud inicial y conformación de grupo',  'descripcion' => 'El grupo se registra y presenta la solicitud inicial de auditoría.', 'orden' => 1],
            ['numero' => 2, 'nombre' => 'Plan de auditoría',                          'descripcion' => 'Elaboración y presentación del plan de auditoría para aprobación docente.', 'orden' => 2],
            ['numero' => 3, 'nombre' => 'Recolección de evidencia',                   'descripcion' => 'Solicitud y análisis de documentos y entrevistas.', 'orden' => 3],
            ['numero' => 4, 'nombre' => 'Informe final',                              'descripcion' => 'Redacción y entrega del informe final de auditoría.', 'orden' => 4],
            ['numero' => 5, 'nombre' => 'Defensa oral',                               'descripcion' => 'Presentación y defensa oral ante el docente.', 'orden' => 5],
        ];

        foreach ($etapas as $etapa) {
            Etapa::create(array_merge($etapa, ['caso_id' => $casoId]));
        }
    }
}
