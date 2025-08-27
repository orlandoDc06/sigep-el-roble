<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalConfiguration;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class LegalConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = LegalConfiguration::query();

        // Filtrar por estado activo
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filtrar por año
        if ($request->filled('year')) {
            $year = $request->year;
            $query->whereYear('start_date', $year);
        }

        $configurations = $query->orderByDesc('start_date')->paginate(10);

        // Obtener años disponibles para el filtro
        $years = LegalConfiguration::selectRaw('YEAR(start_date) as year')
                    ->distinct()
                    ->orderByDesc('year')
                    ->pluck('year');

        return view('admin.legal-configurations.index', [
            'configurations' => $configurations,
            'years' => $years,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $currentConfig = LegalConfiguration::getCurrent();
        
        return view('admin.legal-configurations.create', [
            'configuration' => new LegalConfiguration(),
            'currentConfig' => $currentConfig,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'afp_percentage' => 'required|numeric|min:0|max:100',
            'isss_percentage' => 'required|numeric|min:0|max:100',
            'isss_max_cap' => 'required|numeric|min:0',
            'minimum_wage' => 'required|numeric|min:0',
            'vacation_bonus_percentage' => 'required|numeric|min:0|max:100',
            'year_end_bonus_days' => 'required|integer|min:0|max:365',
            'income_tax_enabled' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        // Si no se especifica income_tax_enabled, por defecto es false
        $validated['income_tax_enabled'] = $request->has('income_tax_enabled');

        // Si se crea como activa, finalizar la configuración anterior
        if ($request->has('is_active') && $request->is_active) {
            $validated['is_active'] = true;
            
            // Finalizar configuración activa anterior
            $currentActive = LegalConfiguration::where('is_active', true)->first();
            if ($currentActive) {
                $currentActive->update([
                    'end_date' => Carbon::parse($validated['start_date'])->subDay(),
                    'is_active' => false,
                ]);
            }
        } else {
            $validated['is_active'] = false;
        }

        LegalConfiguration::create($validated);

        return redirect()
            ->route('admin.legal-configurations.index')
            ->with('success', 'Configuración legal creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LegalConfiguration $legalConfiguration): View
    {
        return view('admin.legal-configurations.show', [
            'configuration' => $legalConfiguration,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LegalConfiguration $legalConfiguration): View
    {
        return view('admin.legal-configurations.edit', [
            'configuration' => $legalConfiguration,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LegalConfiguration $legalConfiguration): RedirectResponse
    {
        $validated = $request->validate([
            'afp_percentage' => 'required|numeric|min:0|max:100',
            'isss_percentage' => 'required|numeric|min:0|max:100',
            'isss_max_cap' => 'required|numeric|min:0',
            'minimum_wage' => 'required|numeric|min:0',
            'vacation_bonus_percentage' => 'required|numeric|min:0|max:100',
            'year_end_bonus_days' => 'required|integer|min:0|max:365',
            'income_tax_enabled' => 'boolean',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['income_tax_enabled'] = $request->has('income_tax_enabled');

        // Manejar activación
        if ($request->has('is_active') && $request->is_active && !$legalConfiguration->is_active) {
            $validated['is_active'] = true;
        } elseif (!$request->has('is_active') && $legalConfiguration->is_active) {
            $validated['is_active'] = false;
        }

        $legalConfiguration->update($validated);

        return redirect()
            ->route('admin.legal-configurations.index')
            ->with('success', 'Configuración legal actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LegalConfiguration $legalConfiguration): RedirectResponse
    {
        // No permitir eliminar configuración activa
        if ($legalConfiguration->is_active) {
            return redirect()
                ->route('admin.legal-configurations.index')
                ->with('error', 'No se puede eliminar la configuración legal activa.');
        }

        $legalConfiguration->delete();

        return redirect()
            ->route('admin.legal-configurations.index')
            ->with('success', 'Configuración legal eliminada exitosamente.');
    }

    /**
     * Activate a specific configuration
     */
    public function activate(LegalConfiguration $legalConfiguration): RedirectResponse
    {
        // Desactivar configuración actual
        LegalConfiguration::where('is_active', true)->update(['is_active' => false]);
        
        // Activar la seleccionada
        $legalConfiguration->update(['is_active' => true]);

        return redirect()
            ->route('admin.legal-configurations.index')
            ->with('success', 'Configuración legal activada exitosamente.');
    }

    /**
     * Duplicate a configuration
     */
    public function duplicate(LegalConfiguration $legalConfiguration): RedirectResponse
    {
        $newConfiguration = $legalConfiguration->replicate();
        $newConfiguration->is_active = false;
        $newConfiguration->start_date = Carbon::now()->startOfYear();
        $newConfiguration->end_date = null;
        $newConfiguration->save();

        return redirect()
            ->route('admin.legal-configurations.edit', $newConfiguration)
            ->with('success', 'Configuración duplicada exitosamente. Ajusta las fechas y valores según necesites.');
    }

    /**
     * Get current legal configuration (API endpoint)
     */
    public function getCurrent()
    {
        $current = LegalConfiguration::getCurrent();
        
        if (!$current) {
            return response()->json([
                'error' => 'No hay configuración legal activa'
            ], 404);
        }

        return response()->json($current);
    }
}