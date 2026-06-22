<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caso;
use App\Models\Documento;
use App\Models\Entrevistado;
use App\Models\Etapa;

class CasoDocumentosEntrevistadosHIPERVIEWSeeder extends Seeder
{
    public function run(): void
    {
        $caso = Caso::firstOrCreate(
            ['codigo' => 'HIPERVIEW'],
            [
                'nombre'      => 'HiperView + Net S.R.L.',
                'descripcion' => 'Proveedor de televisión por cable e internet de Chamical, La Rioja. Sistema EventGES, módulo de facturación y servicios premium.',
                'activo'      => true,
            ]
        );

        if ($caso->documentos()->count() === 0) {
            $documentos = [
                ['codigo' => 'DOC-01', 'titulo' => 'Reseña Institucional - HiperView + Net S.R.L.',           'descripcion' => 'Historia y estructura de la empresa proveedora de cable e internet.'],
                ['codigo' => 'DOC-02', 'titulo' => 'Políticas y Procedimientos Internos - HiperView + Net',   'descripcion' => 'Normativa interna vigente.'],
                ['codigo' => 'DOC-03', 'titulo' => 'Reporte de Eventos y Recaudación 2020-2021',              'descripcion' => 'Registro de eventos y recaudación del período auditado.'],
                ['codigo' => 'DOC-04', 'titulo' => 'Registro de Participantes y Accesos 2020-2021',           'descripcion' => 'Participantes y accesos registrados en el sistema EventGES.'],
                ['codigo' => 'DOC-05', 'titulo' => 'Manual Técnico - Sistema EventGES',                       'descripcion' => 'Documentación técnica del sistema de gestión de eventos.'],
                ['codigo' => 'DOC-06', 'titulo' => 'Informe Técnico de Relevamiento Post-Tormenta',           'descripcion' => 'Informe sobre daños y relevamiento técnico tras evento climático.'],
            ];

            foreach ($documentos as $doc) {
                Documento::create(array_merge($doc, ['caso_id' => $caso->id]));
            }
        }

        if ($caso->entrevistados()->count() === 0) {
            $entrevistados = [
                ['nombre' => 'Sr. Rubén Fernández',         'cargo' => 'Propietario y Gerente General',              'area' => 'Gerencia',        'descripcion_rol' => 'Propietario y máxima autoridad, quien solicita la auditoría.'],
                ['nombre' => 'Sra. Delia Encarnación Castro','cargo' => 'Empleada de Facturación',                   'area' => 'Facturación',     'descripcion_rol' => 'Gestiona la facturación a clientes y obras sociales.'],
                ['nombre' => 'Sr. Daniel García',            'cargo' => 'Instalador de Fibra Óptica',                 'area' => 'Técnica',         'descripcion_rol' => 'Ex Encargado de Sistemas, actualmente instalador de fibra.'],
                ['nombre' => 'Sr. Miguel Palacios',          'cargo' => 'Encargado de Sistemas y Redes',              'area' => 'Sistemas',        'descripcion_rol' => 'Responsable actual de la infraestructura tecnológica.'],
                ['nombre' => 'Sr. Daniel Vera',              'cargo' => 'Administrativo / Atención al Cliente',       'area' => 'Administración',  'descripcion_rol' => 'Gestiona atención al cliente y trámites administrativos.'],
                ['nombre' => 'Sra. Vanesa Romero',           'cargo' => 'Administrativa / Atención al Cliente',       'area' => 'Administración',  'descripcion_rol' => 'Apoyo administrativo y atención al cliente.'],
                ['nombre' => 'Cdor. Ezequiel Bazán',         'cargo' => 'Contador',                                   'area' => 'Finanzas',        'descripcion_rol' => 'Responsable del control contable y financiero.'],
                ['nombre' => 'Sr. Walter Cisneros',          'cargo' => 'Encargado de Ventas de Internet',            'area' => 'Comercial',       'descripcion_rol' => 'Gestiona las ventas del servicio de internet.'],
                ['nombre' => 'Sr. Bruno Achával',            'cargo' => 'Instalador de Fibra Óptica',                 'area' => 'Técnica',         'descripcion_rol' => 'Instalaciones de fibra óptica domiciliaria.'],
                ['nombre' => 'Sr. Hugo Maldonado',           'cargo' => 'Supervisor Técnico de Telecomunicaciones',   'area' => 'Técnica',         'descripcion_rol' => 'Supervisa el área técnica de telecomunicaciones.'],
                ['nombre' => 'Srta. Yamila Torrejón',        'cargo' => 'Oficinista',                                 'area' => 'Administración',  'descripcion_rol' => 'Tareas administrativas generales de oficina.'],
                ['nombre' => 'Sr. Cristian Ávalos',          'cargo' => 'Oficinista',                                 'area' => 'Administración',  'descripcion_rol' => 'Tareas administrativas generales de oficina.'],
            ];

            foreach ($entrevistados as $e) {
                Entrevistado::create(array_merge($e, ['caso_id' => $caso->id]));
            }
        }

        if ($caso->etapas()->count() === 0) {
            $this->crearEtapas($caso->id);
        }

        $this->command->info('HIPERVIEW: documentos, entrevistados y etapas vinculados.');
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
