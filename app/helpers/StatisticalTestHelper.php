<?php

namespace App\Helpers;

class StatisticalTestHelper
{
    public static function mean(array $data): float
    {
        return array_sum($data) / count($data);
    }

    public static function variance(array $data, float $mean): float
    {
        $sum = 0;
        foreach ($data as $val) {
            $sum += pow($val - $mean, 2);
        }
        return $sum / (count($data) - 1);
    }

    public static function standardDeviation(array $data, float $mean): float
    {
        return sqrt(self::variance($data, $mean));
    }

    public static function erf($x)
    {
        $a1 =  0.254829592;
        $a2 = -0.284496736;
        $a3 =  1.421413741;
        $a4 = -1.453152027;
        $a5 =  1.061405429;
        $p  =  0.3275911;

        $sign = $x < 0 ? -1 : 1;
        $x = abs($x);

        $t = 1.0 / (1.0 + $p * $x);
        $y = 1.0 - ((((($a5 * $t + $a4) * $t) + $a3) * $t + $a2) * $t + $a1) * $t * exp(-$x * $x);

        return $sign * $y;
    }

    public static function tDistCDF($t, $df)
    {
        $z = abs($t);
        return 1 - self::erf($z / sqrt(2));
    }

    public static function independentTTestManual(array $group1, array $group2): array
    {
        $n1 = count($group1);
        $n2 = count($group2);

        $mean1 = self::mean($group1);
        $mean2 = self::mean($group2);

        $sd1 = self::standardDeviation($group1, $mean1);
        $sd2 = self::standardDeviation($group2, $mean2);

        $se = sqrt(($sd1 ** 2 / $n1) + ($sd2 ** 2 / $n2));

        if ($se == 0) {
            return [
                't_statistic' => 0,
                'degrees_freedom' => 0,
                'p_value' => 1,
                'is_significant' => false,
                'group_statistics' => [
                    'experiment' => ['mean' => $mean1, 'stddev' => $sd1, 'n' => $n1],
                    'control'    => ['mean' => $mean2, 'stddev' => $sd2, 'n' => $n2],
                ],
                'interpretation' => 'Tidak dapat menghitung T-Statistic karena variasi data terlalu kecil atau sama persis.'
            ];
        }

        $t = ($mean1 - $mean2) / $se;

        $df_numerator = (($sd1 ** 2 / $n1) + ($sd2 ** 2 / $n2)) ** 2;
        $df_denominator = ((($sd1 ** 2 / $n1) ** 2) / ($n1 - 1)) + ((($sd2 ** 2 / $n2) ** 2) / ($n2 - 1));
        $df = $df_numerator / $df_denominator;

        $p = 2 * self::tDistCDF($t, $df);

        $tStatistic = round($t, 4);

        $interpretation = '';

        if ($p < 0.01) {
            $interpretation = "Terdapat perbedaan yang sangat signifikan antara kelompok kontrol dan eksperimen.";
        } elseif ($p < 0.05) {
            $interpretation =  "Terdapat perbedaan yang signifikan antara kelompok kontrol dan eksperimen.";
        } else {
            if (abs($tStatistic) < 1) {
                $interpretation =  "Nilai T-Statistic kecil dan P-Value > 0.05 → tidak ada perbedaan mencolok antara kedua kelompok. Perbedaan tidak signifikan.";
            } else {
                $interpretation =  "P-Value > 0.05 → perbedaan tidak signifikan secara statistik antara kelompok kontrol dan eksperimen.";
            }
        }

        return [
            't_statistic' => $tStatistic,
            'degrees_freedom' => round($df, 2),
            'p_value' => $p,
            'is_significant' => $p < 0.05,
            'group_statistics' => [
                'experiment' => ['mean' => $mean1, 'stddev' => $sd1, 'n' => $n1],
                'control'    => ['mean' => $mean2, 'stddev' => $sd2, 'n' => $n2],
            ],
            'interpretation' => $interpretation
        ];
    }
}
