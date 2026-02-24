# Restore Script - Generated: 02/24/2026 14:20:06
# This script restores files from backup to the project

$projectRoot = "F:\2025\withKushal\sarojshrestha\just-example"
$backupPath = "F:\2025\withKushal\sarojshrestha\just-example\backup-unused-assets-2026-02-24_14-20-06"
$logFile = Join-Path $backupPath "backup-log.txt"

Write-Host "Restoring files from backup..." -ForegroundColor Cyan
Write-Host "Backup Path: $backupPath" -ForegroundColor Yellow
Write-Host ""

if (Test-Path $logFile) {
    $logs = Get-Content $logFile | Select-Object -Skip 2 | Where-Object { $_.Trim() -ne "" }

    foreach ($log in $logs) {
        $parts = $log.Split('|')
        if ($parts.Count -ge 1) {
            $relativePath = $parts[0].Trim()

            $sourcePath = Join-Path $backupPath $relativePath
            $destPath = Join-Path $projectRoot $relativePath

            if (Test-Path $sourcePath) {
                # Create directory structure
                $destDir = Split-Path $destPath -Parent
                if (-not (Test-Path $destDir)) {
                    New-Item -ItemType Directory -Path $destDir -Force | Out-Null
                }

                # Move item back
                Move-Item -Path $sourcePath -Destination $destPath -Force
                Write-Host "  [RESTORED] $relativePath" -ForegroundColor Green
            }
        }
    }
} else {
    Write-Host "Log file not found: $logFile" -ForegroundColor Red
}

Write-Host ""
Write-Host "Restore complete!" -ForegroundColor Green
Write-Host "You can now delete the backup folder if desired: Remove-Item -Path $backupPath -Recurse -Force" -ForegroundColor Yellow
