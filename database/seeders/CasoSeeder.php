<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Caso;
use App\Models\Documento;
use App\Models\Entrevistado;
use App\Models\Etapa;

class CasoSeeder extends Seeder
{
    public function run(): void
    {
        $this->casoFMC();
        $this->casoCSIV();
        $this->casoCSIL();
    }

    // ─────────────────────────────────────────
    // CASO 1 — Ferretería Mayorista del Centro
    // ─────────────────────────────────────────
    private function casoFMC(): void
    {
        $caso = Caso::create([
            'codigo'      => 'FMC',
            'nombre'      => 'Ferretería Mayorista del Centro S.A.',
            'descripcion' => 'Empresa mayorista ferretera de Chamical, La Rioja. Sistema de gestión de inventario FMCGEST.',
            'activo'      => true,
        ]);

        // Documentos
        $documentos = [
            ['codigo' => 'DOC-01', 'titulo' => 'Manual de usuario FMCGEST',                      'descripcion' => 'Manual completo del sistema de gestión de inventario.'],
            ['codigo' => 'DOC-02', 'titulo' => 'Registro de accesos remotos',                    'descripcion' => 'Log de conexiones VPN y accesos remotos al sistema.'],
            ['codigo' => 'DOC-03', 'titulo' => 'Historial de ajustes manuales de stock',         'descripcion' => 'Movimientos manuales realizados sobre el inventario.'],
            ['codigo' => 'DOC-04', 'titulo' => 'Contrato con Distribuidora El Fortín',           'descripcion' => 'Documentación del proveedor y órdenes de compra asociadas.'],
            ['codigo' => 'DOC-05', 'titulo' => 'Política de control de accesos',                 'descripcion' => 'Normativa interna sobre permisos y roles en el sistema.'],
            ['codigo' => 'DOC-06', 'titulo' => 'Informe de auditoría interna 2022',              'descripcion' => 'Último informe de auditoría interna disponible.'],
        ];

        foreach ($documentos as $doc) {
            Documento::create(array_merge($doc, ['caso_id' => $caso->id]));
        }

        // Entrevistados
        $entrevistados = [
            ['nombre' => 'Fabio Herrera',    'cargo' => 'Gerente de Logística',  'area' => 'Logística',  'descripcion_rol' => 'Responsable de la gestión de inventario y relación con proveedores.'],
            ['nombre' => 'Diego Ramos',      'cargo' => 'Jefe de Sistemas',      'area' => 'Sistemas',   'descripcion_rol' => 'Administrador del sistema FMCGEST y la infraestructura tecnológica.'],
            ['nombre' => 'Laura Medina',     'cargo' => 'Contadora General',     'area' => 'Finanzas',   'descripcion_rol' => 'Responsable del control financiero y conciliación de cuentas.'],
            ['nombre' => 'Carlos Pereyra',   'cargo' => 'Gerente General',       'area' => 'Gerencia',   'descripcion_rol' => 'Máxima autoridad de la empresa, quien solicita la auditoría.'],
            ['nombre' => 'Ana Rodríguez',    'cargo' => 'Jefa de Compras',       'area' => 'Compras',    'descripcion_rol' => 'Gestiona las órdenes de compra y relación con proveedores habilitados.'],
        ];

        foreach ($entrevistados as $e) {
            Entrevistado::create(array_merge($e, ['caso_id' => $caso->id]));
        }

        // Etapas
        $this->crearEtapas($caso->id);
    }

    // ─────────────────────────────────────────
    // CASO 2 — Centro de Salud Integral del Valle
    // ─────────────────────────────────────────
    private function casoCSIV(): void
    {
        $caso = Caso::create([
            'codigo'      => 'CSIV',
            'nombre'      => 'Centro de Salud Integral del Valle S.R.L.',
            'descripcion' => 'Clínica privada de Chamical, La Rioja. Sistema de liquidación de honorarios médicos SaludGest.',
            'activo'      => true,
        ]);

        // Documentos
        $documentos = [
            ['codigo' => 'DOC-01', 'titulo' => 'Manual de configuración SaludGest',              'descripcion' => 'Parámetros del sistema y guía de configuración.'],
            ['codigo' => 'DOC-02', 'titulo' => 'Liquidaciones de honorarios Q1 2024',            'descripcion' => 'Detalle de liquidaciones del primer trimestre 2024.'],
            ['codigo' => 'DOC-03', 'titulo' => 'Informe de auditoría 2021',                      'descripcion' => 'Auditoría anterior con recomendación REC-04 pendiente de implementación.'],
            ['codigo' => 'DOC-04', 'titulo' => 'Contratos con obras sociales',                   'descripcion' => 'Convenios vigentes y porcentajes de descuento acordados.'],
            ['codigo' => 'DOC-05', 'titulo' => 'Registro de anulaciones de facturación',         'descripcion' => 'Facturas anuladas en el período auditado.'],
            ['codigo' => 'DOC-06', 'titulo' => 'Política de liquidación de honorarios',          'descripcion' => 'Normativa interna sobre el cálculo de honorarios médicos.'],
        ];

        foreach ($documentos as $doc) {
            Documento::create(array_merge($doc, ['caso_id' => $caso->id]));
        }

        // Entrevistados
        $entrevistados = [
            ['nombre' => 'Miriam Soria',      'cargo' => 'Directora Médica',         'area' => 'Dirección',    'descripcion_rol' => 'Máxima autoridad clínica, quien solicita la auditoría.'],
            ['nombre' => 'Roberto Figueroa',  'cargo' => 'Administrador del Sistema', 'area' => 'Sistemas',     'descripcion_rol' => 'Responsable de la configuración y mantenimiento de SaludGest.'],
            ['nombre' => 'Claudia Ríos',      'cargo' => 'Jefa de Facturación',      'area' => 'Facturación',  'descripcion_rol' => 'Gestiona la facturación a obras sociales y pacientes particulares.'],
            ['nombre' => 'Jorge Villalba',    'cargo' => 'Contador',                 'area' => 'Finanzas',     'descripcion_rol' => 'Responsable del control financiero y liquidaciones.'],
            ['nombre' => 'Patricia Molina',   'cargo' => 'Médica de Planta',         'area' => 'Médica',       'descripcion_rol' => 'Representa al cuerpo médico afectado por las liquidaciones incorrectas.'],
        ];

        foreach ($entrevistados as $e) {
            Entrevistado::create(array_merge($e, ['caso_id' => $caso->id]));
        }

        // Etapas
        $this->crearEtapas($caso->id);
    }

    // ─────────────────────────────────────────
    // CASO 3 — Centro de Salud Integral de los Llanos S.R.L.
    // ─────────────────────────────────────────
    private function casoCSIL(): void
    {
        $caso = Caso::create([
            'codigo'      => 'CSIL',
            'nombre'      => 'Centro de Salud Integral de los Llanos S.R.L.',
            'descripcion' => 'Centro de salud privado de La Rioja. Sistema de liquidación de honorarios médicos SaludGest.',
            'activo'      => true,
        ]);

        // Documentos
        $documentos = [
            ['codigo' => 'DOC-01', 'titulo' => 'Trabajo Practico - Consigna Caso 2',                  'descripcion' => 'Consigna del trabajo práctico para el caso CSIL.'],
            ['codigo' => 'DOC-02', 'titulo' => 'Resena Institucional - CSIL S.R.L.',                  'descripcion' => 'Historia y estructura de la organización.'],
            ['codigo' => 'DOC-03', 'titulo' => 'Manual de Politicas y Procedimientos Vigentes',       'descripcion' => 'Normativa interna vigente.'],
            ['codigo' => 'DOC-04', 'titulo' => 'Reporte de Facturacion Trimestral',                   'descripcion' => 'Facturación del trimestre auditado.'],
            ['codigo' => 'DOC-05', 'titulo' => 'Reporte de Liquidaciones del Sistema SaludGest',      'descripcion' => 'Detalle de liquidaciones generadas por el sistema.'],
            ['codigo' => 'DOC-06', 'titulo' => 'Manual Tecnico - Modulo de Liquidacion SaludGest',   'descripcion' => 'Documentación técnica del módulo de liquidación.'],
            ['codigo' => 'DOC-07', 'titulo' => 'Informe de Auditoria Operativa 2021',                 'descripcion' => 'Auditoría anterior con recomendaciones pendientes.'],
        ];

        foreach ($documentos as $doc) {
            Documento::create(array_merge($doc, ['caso_id' => $caso->id]));
        }

        // Entrevistados
        $entrevistados = [
            ['nombre' => 'Dra. Carmen Valdes',    'cargo' => 'Directora Medica y Socia Gerente',      'area' => 'Dirección',     'descripcion_rol' => 'Máxima autoridad, quien solicita la auditoría.'],
            ['nombre' => 'Sr. Pablo Rios',         'cargo' => 'Administrador General',                 'area' => 'Administración','descripcion_rol' => 'Gestiona los recursos administrativos y humanos.'],
            ['nombre' => 'Sra. Lucia Ferrer',      'cargo' => 'Contadora',                             'area' => 'Finanzas',      'descripcion_rol' => 'Responsable del control financiero y liquidaciones.'],
            ['nombre' => 'Sr. Martin Ochoa',       'cargo' => 'Jefe de Sistemas e Informatica',        'area' => 'Sistemas',      'descripcion_rol' => 'Administrador del sistema SaludGest.'],
            ['nombre' => 'Dr. Esteban Ruiz',       'cargo' => 'Medico Clinico',                        'area' => 'Médica',        'descripcion_rol' => 'Representa al cuerpo médico afectado por liquidaciones.'],
            ['nombre' => 'Dra. Ana Pereyra',       'cargo' => 'Medica Pediatra',                       'area' => 'Médica',        'descripcion_rol' => 'Médica de planta con observaciones sobre honorarios.'],
            ['nombre' => 'Sra. Rosa Mamani',       'cargo' => 'Recepcionista',                         'area' => 'Recepción',     'descripcion_rol' => 'Primer contacto con pacientes, maneja turnos.'],
            ['nombre' => 'Sr. Jorge Ibanez',       'cargo' => 'Tecnico de Laboratorio',                'area' => 'Laboratorio',   'descripcion_rol' => 'Responsable del laboratorio clínico.'],
            ['nombre' => 'Sra. Patricia Suarez',   'cargo' => 'Facturadora de Obras Sociales',         'area' => 'Facturación',   'descripcion_rol' => 'Gestiona la facturación a obras sociales.'],
            ['nombre' => 'Sr. Nicolas Leiva',      'cargo' => 'Tecnico de Soporte IT',                 'area' => 'Sistemas',      'descripcion_rol' => 'Soporte técnico del sistema SaludGest.'],
        ];

        foreach ($entrevistados as $e) {
            Entrevistado::create(array_merge($e, ['caso_id' => $caso->id]));
        }

        // Etapas
        $this->crearEtapas($caso->id);
    }

    // ─────────────────────────────────────────
    // Etapas — iguales para ambos casos
    // ─────────────────────────────────────────
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