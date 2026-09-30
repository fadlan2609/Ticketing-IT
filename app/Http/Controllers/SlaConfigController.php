<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\SlaConfig;
use App\Models\WorkingHour;
use Illuminate\Http\Request;

class SlaConfigController extends Controller
{
    public function index()
    {
        $configs = SlaConfig::orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")->get();
        $workingHours = WorkingHour::orderBy('day_of_week')->get();
        $holidays = Holiday::orderBy('date')->get();

        return view('settings.sla.index', compact('configs', 'workingHours', 'holidays'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'configs' => ['required', 'array'],
            'configs.*.id' => ['required', 'exists:sla_configs,id'],
            'configs.*.response_minutes' => ['required', 'integer', 'min:1'],
            'configs.*.resolution_minutes' => ['required', 'integer', 'min:1'],
        ]);

        foreach ($data['configs'] as $configData) {
            SlaConfig::where('id', $configData['id'])->update([
                'response_minutes' => $configData['response_minutes'],
                'resolution_minutes' => $configData['resolution_minutes'],
            ]);
        }

        return back()->with('success', 'Konfigurasi SLA berhasil diperbarui.');
    }

    public function updateWorkingHours(Request $request)
    {
        $data = $request->validate([
            'working_hours' => ['required', 'array'],
            'working_hours.*.id' => ['required', 'exists:working_hours,id'],
            'working_hours.*.start_time' => ['required'],
            'working_hours.*.end_time' => ['required'],
            'working_hours.*.is_working_day' => ['nullable', 'boolean'],
        ]);

        foreach ($data['working_hours'] as $whData) {
            WorkingHour::where('id', $whData['id'])->update([
                'start_time' => $whData['start_time'],
                'end_time' => $whData['end_time'],
                'is_working_day' => !empty($whData['is_working_day']),
            ]);
        }

        return back()->with('success', 'Jam kerja berhasil diperbarui.');
    }

    public function storeHoliday(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date', 'unique:holidays,date'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Holiday::create($data);

        return back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroyHoliday(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Hari libur dihapus.');
    }
}