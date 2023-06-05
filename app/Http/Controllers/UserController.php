<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Method Register
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data["password"] = Hash::make($data["password"]);

        $user = User::create($data);
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success(
            [
                "user" => $user,
                "token" => $token->plainTextToken,
            ],
            "User berhasil register"
        );
    }

    /**
     * Method Login
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $isValid = $this->isValidCredential($request);

        if (!$isValid["success"]) {
            return $this->error(
                $isValid["message"],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = $isValid["user"];
        $token = $user->createToken(User::USER_TOKEN);

        return $this->success(
            [
                "user" => $user,
                "token" => $token->plainTextToken,
            ],
            "Login berhasil"
        );
    }

    /**
     * Cek Validitas User antara inputan user dengan data di database
     *
     * @param LoginRequest $request
     * @return array
     */
    private function isValidCredential(LoginRequest $request): array
    {
        $data = $request->validated();

        $user = User::where("email", $data["email"])->first();
        if ($user == null) {
            return [
                "success" => false,
                "message" => "Email tidak ditemukan",
            ];
        }

        if (Hash::check($data["password"], $user->password)) {
            return [
                "success" => true,
                "user" => $user,
            ];
        }

        return [
            "success" => false,
            "message" => "Password yang Anda masukkan salah",
        ];
    }

    /**
     * Login With Token (Auto Login)
     *
     * @return JsonResponse
     */
    public function loginWithToken(): JsonResponse
    {
        return $this->success(auth()->user(), "Login Berhasil");
    }

    /**
     * logout
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return $this->success(null, "logout Berhasil");
    }

    public function updatePhoneNumber(Request $request, $id)
    {
        $phoneNumberValidated = $request->validate([
            "phone_number" => "string|unique:users,phone_number",
        ]);

        $user = User::findOrFail($id);

        $phoneNumber = $phoneNumberValidated["phone_number"];

        $user->update(["phone_number" => $phoneNumber]);

        return $this->success($user);
    }

    public function updateProfileImageUrl(Request $request, $id)
    {
        $profileImageValidated = $request->validate([
            "profile_image" => "string",
        ]);

        $user = User::findOrFail($id);

        $profileImage = $profileImageValidated["profile_image"];

        $user->update(["profile_image" => $profileImage]);

        return $this->success($user);
    }

    public function checkPhoneNumber(Request $request)
    {
        $isAvailable = User::where(
            "phone_number",
            $request["phone_number"]
        )->firstOr(function () {
            return false;
        });

        if ($isAvailable) {
            return $this->success($isAvailable);
        } else {
            return $this->error("Tidak ada Nomor telepon yang ditemukan", 400);
        }
    }

    public function checkUserPhoneNumber($id)
    {
        $user = User::findOrFail($id);

        if ($user->phone_number == null) {
            return $this->error("Nomor telepon tidak ditemukan!", 400);
        }

        return $this->success($user);
    }

    // Kalau sudah ada google_id -> LOGIN, Kalau belum -> Update & Login
    public function updateGoogleId(Request $request, $id)
    {
        $googleId = $request["google_id"];

        $findUser = User::where("google_id", $googleId)->firstOr(function () {
            return false;
        });

        if ($findUser) {
            // Jika user Sudah punya google_id -> LOGIN
            // login
            $token = $findUser->createToken(User::USER_TOKEN);

            return $this->success(
                [
                    "user" => $findUser,
                    "token" => $token->plainTextToken,
                ],
                "Login berhasil"
            );
            return $this->success($findUser);
        } else {
            // Jika user belum punya google_id -> Update
            // update
            $user = User::findOrFail($id);
            $user->update(["google_id" => $googleId]);

            // login
            // $token = $user->createToken(User::USER_TOKEN);

            // return $this->success(
            //     [
            //         "user" => $user,
            //         "token" => $token->plainTextToken,
            //     ],
            //     "Login berhasil"
            // );
            // return $this->success($user);
        }
    }

    public function checkGoogleId(Request $request)
    {
        $isAvailable = User::where("google_id", $request["google_id"])->firstOr(
            function () {
                return false;
            }
        );

        if ($isAvailable) {
            return $this->success($isAvailable);
        } else {
            return $this->error("Tidak ada Google_id yang ditemukan", 400);
        }
    }

    public function updateName(Request $request, $id)
    {
        $nameValidated = $request->validate([
            "name" => "string|min:3",
        ]);

        $user = User::findOrFail($id);

        $name = $nameValidated["name"];

        $user->update(["name" => $name]);

        return $this->success($user);
    }

    public function uploadProfileImage(Request $request)
    {
        $dir = "profile-images/";
        $image = $request->file("image");
        $id = $request->id;

        $user = User::findOrFail($id);
        $imageUrl = "profile-images/$user->profile_image";

        if ($request->has("image")) {
            $imageName =
                $this->generateRandomString(30) . "-" . uniqid() . "." . "png";

            // Jika folder blm dibuat
            if (!Storage::disk("public")->exists($dir)) {
                Storage::disk("public")->makeDirectory($dir);
            }
            Storage::disk("public")->put(
                $dir . $imageName,
                file_get_contents($image)
            );

            // Jika ada gambar lama
            if ($imageUrl != null) {
                Storage::disk("public")->delete($imageUrl);
            }
        } else {
            return $this->error("No Image!", 400);
        }

        User::where(["id" => $id])->update([
            "profile_image" => $imageName,
        ]);

        $user = User::findOrFail($id);

        return $this->success($user);
    }
}
