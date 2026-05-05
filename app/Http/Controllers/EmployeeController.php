<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->get();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip'  => 'nullable|string|max:255',
        ]);

        Employee::create([
            'nama' => $request->nama,
            'nip'  => $request->nip,
            'is_active' => 1,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip'  => 'nullable|string|max:255',
        ]);

        $employee->update([
            'nama' => $request->nama,
            'nip'  => $request->nip,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Pegawai berhasil diupdate');
    }

    public function destroy(Employee $employee)
    {
        $employee->update([
            'is_active' => 0
        ]);

        return redirect()->route('employees.index')
            ->with('error', 'Pegawai dinonaktifkan');
    }

    public function activate(Employee $employee)
    {
        $employee->update([
            'is_active' => 1
        ]);

        return back()->with('success', 'Pegawai diaktifkan kembali');
    }
}