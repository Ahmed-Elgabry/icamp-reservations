<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Aws\S3\S3Client;

class S3MultipartUploadController extends Controller
{
    // أقل حجم للجزء الواحد في S3 (ما عدا الأخير)
    private int $minPartSize = 5 * 1024 * 1024;

    private function s3Client(): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'eu-central'),
            'endpoint'=> env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function createMultipart(Request $request)
    {
        $validated = $request->validate([
            'fileName'    => 'required|string|max:255',
            'contentType' => 'required|string|max:255',
            'fileSize'    => 'required|integer|min:1',
            'prefix'      => 'nullable|string|max:255',
        ]);

        $allowed = [
            'video/mp4','video/webm','video/avi','video/quicktime','video/x-matroska',
            'video/x-flv','video/3gpp','video/mpeg','video/mpg','video/x-ms-wmv'
        ];
        if (!in_array(strtolower($validated['contentType']), $allowed)) {
            return response()->json(['message' => 'Unsupported content type'], 422);
        }

        try {
            $client = $this->s3Client();
            $bucket = env('AWS_BUCKET');

            $prefix   = $validated['prefix'] ?: 'uploads/signin/videos';
            $safeName = Str::uuid()->toString() . '_' . preg_replace('/[^\w\-.]+/u', '_', $validated['fileName']);
            $key      = trim($prefix, '/').'/'.$safeName;

            $result = $client->createMultipartUpload([
                'Bucket'      => $bucket,
                'Key'         => $key,
                'ContentType' => $validated['contentType'],
            ]);

            return response()->json([
                'uploadId'    => $result['UploadId'],
                'key'         => $key,
                'bucket'      => $bucket,
                'partMinSize' => $this->minPartSize
            ]);
        } catch (\Throwable $e) {
            \Log::error("Multipart error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function signPart(Request $request)
    {
        $validated = $request->validate([
            'key'        => 'required|string',
            'uploadId'   => 'required|string',
            'partNumber' => 'required|integer|min:1',
        ]);

        try {
            $client = $this->s3Client();
            $bucket = env('AWS_BUCKET');

            $cmd = $client->getCommand('UploadPart', [
                'Bucket'     => $bucket,
                'Key'        => $validated['key'],
                'UploadId'   => $validated['uploadId'],
                'PartNumber' => (int)$validated['partNumber'],
            ]);

            $requestSigned = $client->createPresignedRequest($cmd, '+60 minutes');
            $url = (string)$requestSigned->getUri();

            return response()->json(['url' => $url]);
        } catch (\Throwable $e) {
            \Log::error("SignPart error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function completeMultipart(Request $request)
    {
        $validated = $request->validate([
            'key'      => 'required|string',
            'uploadId' => 'required|string',
            'parts'    => 'required|array|min:1',
            'parts.*.PartNumber' => 'required|integer|min:1',
            'parts.*.ETag'       => 'required|string',
        ]);

        try {
            $client = $this->s3Client();
            $bucket = env('AWS_BUCKET');

            $parts = collect($validated['parts'])
                ->sortBy('PartNumber')
                ->values()
                ->all();

            $result = $client->completeMultipartUpload([
                'Bucket'   => $bucket,
                'Key'      => $validated['key'],
                'UploadId' => $validated['uploadId'],
                'MultipartUpload' => ['Parts' => $parts],
            ]);

            return response()->json([
                'location' => $result['Location'] ?? null,
                'key'      => $validated['key'],
                'bucket'   => $bucket,
            ]);
        } catch (\Throwable $e) {
            \Log::error("CompleteMultipart error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function abortMultipart(Request $request)
    {
        $validated = $request->validate([
            'key'      => 'required|string',
            'uploadId' => 'required|string',
        ]);

        try {
            $client = $this->s3Client();
            $bucket = env('AWS_BUCKET');

            $client->abortMultipartUpload([
                'Bucket'   => $bucket,
                'Key'      => $validated['key'],
                'UploadId' => $validated['uploadId'],
            ]);

            return response()->json(['aborted' => true]);
        } catch (\Throwable $e) {
            \Log::error("AbortMultipart error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
