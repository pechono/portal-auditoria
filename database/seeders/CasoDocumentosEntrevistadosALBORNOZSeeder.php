<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caso;
use App\Models\Documento;
use App\Models\Entrevistado;
use App\Models\Etapa;

class CasoDocumentosEntrevistadosALBORNOZSeeder extends Seeder
{
    public function run(): void
    {
        $caso = Caso::firstOrCreate(
            ['codigo' => 'ALBORNOZ'],
            [
                'nombre'      => 'Distribuidora de Repuestos de Alto Valor Albornoz S.A.',
                'descripcion' => 'Importadora de autopartes de alto valor con sede en Buenos Aires y depósitos regionales en Tucumán y Mendoza. Sistema GunterIX 3.1, módulos de stock, logística y flota.',
                'activo'      => true,
            ]
        );

        if ($caso->documentos()->count() === 0) {
            $documentos = [
                ['codigo' => 'DOC-01', 'titulo' => 'Reseña Institucional - Distribuidora Albornoz',           'descripcion' => 'Historia y estructura de la distribuidora de autopartes.'],
                ['codigo' => 'DOC-02', 'titulo' => 'Políticas y Procedimientos Internos - Distribuidora Albornoz', 'descripcion' => 'Normativa interna vigente.'],
                ['codigo' => 'DOC-03', 'titulo' => 'Reporte de Stock - Depósito Mendoza',                     'descripcion' => 'Stock actual y movimientos del depósito regional Mendoza.'],
                ['codigo' => 'DOC-04', 'titulo' => 'Reporte de Recepción - Depósito Tucumán',                 'descripcion' => 'Recepciones registradas en el depósito regional Tucumán.'],
                ['codigo' => 'DOC-05', 'titulo' => 'Reporte de Combustible y Viáticos - Flota Propia',        'descripcion' => 'Gastos de combustible y viáticos de la flota de camiones propia.'],
                ['codigo' => 'DOC-06', 'titulo' => 'Tabla de Distancias Oficiales y Consumo Estándar por Ruta','descripcion' => 'Distancias oficiales y consumo esperado para cada ruta de distribución.'],
                ['codigo' => 'DOC-07', 'titulo' => 'Manual Técnico - Sistema GunterIX 3.1',                   'descripcion' => 'Documentación técnica del sistema de gestión.'],
                ['codigo' => 'DOC-08', 'titulo' => 'Propuesta de Actualización a GunterIX 4.0',               'descripcion' => 'Propuesta comercial para migrar al sistema GunterIX 4.0.'],
                ['codigo' => 'DOC-09', 'titulo' => 'Comunicación con Casa Central - Reclamo de Devolución',   'descripcion' => 'Correspondencia con la casa central alemana sobre reclamo de devolución.'],
            ];

            foreach ($documentos as $doc) {
                Documento::create(array_merge($doc, ['caso_id' => $caso->id]));
            }
        }

        if ($caso->entrevistados()->count() === 0) {
            $entrevistados = [
                ['nombre' => 'Sr. Aníbal Albornoz',    'cargo' => 'Propietario y Gerente General',          'area' => 'Gerencia',        'descripcion_rol' => 'Propietario de la distribuidora, quien solicita la auditoría.'],
                ['nombre' => 'Sra. Valeria Núñez',     'cargo' => 'Gerente de Logística',                   'area' => 'Logística',       'descripcion_rol' => 'Responsable de la logística y coordinación de flota.'],
                ['nombre' => 'Sr. Matías Lescano',     'cargo' => 'Encargado de Depósito Buenos Aires',     'area' => 'Depósito',        'descripcion_rol' => 'Gestiona el depósito central en Buenos Aires.'],
                ['nombre' => 'Sr. Joaquín Espeche',    'cargo' => 'Encargado de Depósito Tucumán',          'area' => 'Depósito',        'descripcion_rol' => 'Gestiona el depósito regional de Tucumán.'],
                ['nombre' => 'Sr. Damián Quiroga',     'cargo' => 'Encargado de Depósito Mendoza',          'area' => 'Depósito',        'descripcion_rol' => 'Gestiona el depósito regional de Mendoza.'],
                ['nombre' => 'Sr. Ramón Sosa',         'cargo' => 'Chofer - Ruta Buenos Aires / Mendoza',   'area' => 'Flota',           'descripcion_rol' => 'Conductor de camión en la ruta Buenos Aires - Mendoza.'],
                ['nombre' => 'Sr. Leonel Funes',       'cargo' => 'Chofer - Ruta Buenos Aires / NOA',       'area' => 'Flota',           'descripcion_rol' => 'Conductor de camión en la ruta Buenos Aires - Tucumán y NOA.'],
                ['nombre' => 'Sr. Adrián Coronel',     'cargo' => 'Chofer - Ruta Tucumán / Mendoza',        'area' => 'Flota',           'descripcion_rol' => 'Conductor de camión en la ruta Tucumán - Mendoza.'],
                ['nombre' => 'Cra. Mariana Sotelo',    'cargo' => 'Contadora',                              'area' => 'Finanzas',        'descripcion_rol' => 'Responsable del control contable y financiero.'],
                ['nombre' => 'Srta. Florencia Aguirre','cargo' => 'Administrativa',                         'area' => 'Administración',  'descripcion_rol' => 'Tareas administrativas generales.'],
                ['nombre' => 'Sr. Norberto Cabral',    'cargo' => 'Empleado de Depósito Buenos Aires',      'area' => 'Depósito',        'descripcion_rol' => 'Operario del depósito central en Buenos Aires.'],
                ['nombre' => 'Sr. Walter Gimenez',     'cargo' => 'Mecánico de Flota',                      'area' => 'Flota',           'descripcion_rol' => 'Responsable del mantenimiento de los camiones de flota.'],
                ['nombre' => 'Sr. Hernán Paz',         'cargo' => 'Vendedor Comercial',                     'area' => 'Comercial',       'descripcion_rol' => 'Gestiona las ventas a distribuidores y clientes.'],
                ['nombre' => 'Sra. Liliana Bértola',   'cargo' => 'Recursos Humanos',                       'area' => 'RRHH',            'descripcion_rol' => 'Gestiona el personal y liquidación de sueldos.'],
            ];

            foreach ($entrevistados as $e) {
                Entrevistado::create(array_merge($e, ['caso_id' => $caso->id]));
            }
        }

        if ($caso->etapas()->count() === 0) {
            $this->crearEtapas($caso->id);
        }

        $this->command->info('ALBORNOZ: documentos, entrevistados y etapas vinculados.');
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
