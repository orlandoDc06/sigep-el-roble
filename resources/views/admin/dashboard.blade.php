@extends('layouts.app')



@section('contenido')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Contenido Principal -->
        <div class="bg-white rounded-b-lg shadow-lg">
            
          
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Panel de Administración</h3>
                        <p class="text-gray-600 mt-1">Gestión de empleados, turnos, sucursales y más.</p>
                    </div>
                </div>
            </div>

            <!-- Secciones de Configuración -->
            <div class="p-8 space-y-8">
                
                <!-- Gestión de Personal -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 ml-2">Gestión de Personal</h3>
                            <p class="text-gray-600 text-sm ml-2">Administra empleados, usuarios y asistencias</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('employees.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 p-2 rounded-lg group-hover:bg-blue-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Empleados</p>
                                    <p class="text-xs text-gray-500">Gestionar empleados</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('users.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 p-2 rounded-lg group-hover:bg-green-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Usuarios</p>
                                    <p class="text-xs text-gray-500">Gestionar usuarios</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('attendances.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-purple-500 rounded-lg group-hover:bg-purple-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                     <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                            </div>
                                <div>
                                    <p class="font-medium text-gray-900">Asistencias</p>
                                    <p class="text-xs text-gray-500">Control de asistencias</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.justified-absences') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-500  rounded-lg group-hover:bg-orange-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                     <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Permisos</p>
                                    <p class="text-xs text-gray-500">Permisos y ausencias</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Configuración Organizacional -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 ml-2">Configuración Organizacional</h3>
                            <p class="text-gray-600 text-sm ml-2">Sucursales, turnos y roles del sistema</p>
                        </div>
                    </div>
    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('branches.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 p-2 rounded-lg group-hover:bg-green-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Sucursales</p>
                                    <p class="text-xs text-gray-500">Gestionar ubicaciones</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('shifts.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-500 rounded-lg group-hover:bg-orange-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Turnos</p>
                                    <p class="text-xs text-gray-500">Horarios de trabajo</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.roles.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-red-200 hover:border-red-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-red-500 p-2 rounded-lg group-hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Roles y Permisos</p>
                                    <p class="text-xs text-gray-500">Gestionar accesos</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Gestión Financiera -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 ml-2">Gestión Financiera</h3>
                            <p class="text-gray-600 text-sm ml-2">Bonos, descuentos, anticipos y planillas</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('bonuses.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 p-2 rounded-lg group-hover:bg-green-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Bonos</p>
                                    <p class="text-xs text-gray-500">Gestionar bonificaciones</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('special-days.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-600 p-2 rounded-lg group-hover:bg-orange-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 011 1v1h6V3a1 1 0 112 0v1h1a2 2 0 012 2v1H3V6a2 2 0 012-2h1V3a1 1 0 011-1zM3 9h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V9zm4 2a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Dias Festivos</p>
                                    <p class="text-xs text-gray-500"></p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('bonuses-assignments.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 p-2 rounded-lg group-hover:bg-blue-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Bonos Asignados</p>
                                    <p class="text-xs text-gray-500">Ver asignaciones</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('deductions.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 p-2 rounded-lg group-hover:bg-green-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Descuentos</p>
                                    <p class="text-xs text-gray-500">Gestionar deducciones</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('deductions-assignments.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-yellow-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-500 rounded-lg group-hover:bg-orange-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                     <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>

                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Descuentos Asignados</p>
                                    <p class="text-xs text-gray-500">Ver asignaciones</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- gestión financiera -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        <a href="{{ route('advances.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 rounded-lg group-hover:bg-green-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Anticipos</p>
                                    <p class="text-xs text-gray-500">Gestión de anticipos</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('payrolls.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-green-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-500 rounded-lg group-hover:bg-green-600 transition-colors">
                                   <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Planillas</p>
                                    <p class="text-xs text-gray-500">Gestión de nóminas</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('change-logs.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-gray-500 p-2 rounded-lg group-hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Registro de Cambios</p>
                                    <p class="text-xs text-gray-500">Historial de cambios</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Configuración Avanzada -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-gray-100 p-3 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 ml-2">Configuración Avanzada</h3>
                            <p class="text-gray-600 text-sm ml-2">Configuraciones legales, fórmulas y parámetros del sistema</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.legal-configurations.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-500 rounded-lg group-hover:bg-blue-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2L3 7v11c0 5.55 3.84 2.97 9 2 5.16.97 9 3.55 9-2V7l-7-5zM6 9.99h8a1 1 0 110 2H6a1 1 0 110-2z" clip-rule="evenodd"/>
                                </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Configuraciones Legales</p>
                                    <p class="text-xs text-gray-500">Parámetros legales y normativos</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.formulas.index') }}" 
                           class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="bg-indigo-500 rounded-lg group-hover:bg-indigo-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8zm0 4a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1v-2z" clip-rule="evenodd"/>
                                </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Fórmulas</p>
                                    <p class="text-xs text-gray-500">Fórmulas de cálculo salariales</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection