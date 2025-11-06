<?php

namespace App\Http\Controllers;

use App\Models\{Report, Barangay};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log, Storage};
use Illuminate\Validation\ValidationException;
use App\Helpers\GeoapifyHelper;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'latitude'  => 'required|numeric',
                'longitude' => 'required|numeric',
                'type'      => 'required|string|in:flood,fire,earthquake,typhoon,landslide',
                'severity'  => 'required|string|in:low,moderate,high,critical',
                'content'   => 'required|string|min:10',
                'media'     => 'nullable|image|max:5120',
                'contact'   => 'nullable|string|max:255',
                'name'      => 'nullable|string|max:255',
                'affected_count' => 'nullable|integer|min:0',
            ]);

            $latitude = round($validated['latitude'], 6);
            $longitude = round($validated['longitude'], 6);

            $geo = GeoapifyHelper::reverseGeocode($latitude, $longitude);
            Log::info('Geoapify Reverse Result', $geo);

            if (!$geo || (!$geo['barangay'] && !$geo['municipality'])) {
                return back()->withErrors(['location' => 'Unable to identify location. Please try again.'])->withInput();
            }

            $barangay = Barangay::firstOrCreate(
                [
                    'name' => $geo['barangay'],
                    'municipality' => $geo['municipality'],
                    'province' => $geo['province'],
                ],
                [
                    'risk_level' => 'low',
                    'latitude'   => $latitude,
                    'longitude'  => $longitude,
                ]
            );

            $imagePath = null;
            if ($request->hasFile('media') && $request->file('media')->isValid()) {
                $imagePath = $request->file('media')->store('reports', 'public');
            }

            $report = Report::create([
                'user_id'       => auth()->id(),
                'barangay_id'   => $barangay->barangay_id ?? $barangay->id,
                'latitude'      => $latitude,
                'longitude'     => $longitude,
                'type'          => $validated['type'],
                'severity'      => $validated['severity'],
                'content'       => $validated['content'],
                'image_path'    => $imagePath,
                'contact'       => $validated['contact'] ?? null,
                'name'          => $validated['name'] ?? null,
                'affected_count'=> $validated['affected_count'] ?? null,
                'reported_at'   => now(),
            ]);

            return redirect()
                ->route('community.awareness')
                ->with('success', 'Your report has been successfully submitted!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            return back()->with('error', 'Something went wrong while submitting your report. Please try again.')->withInput();
        } finally {
            //
        }
    }
}
