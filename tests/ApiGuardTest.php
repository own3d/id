<?php

declare(strict_types=1);

namespace Own3d\Id\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Own3d\Id\Auth\Own3dIdGuard;
use Own3d\Id\Tests\TestCases\TestCase;

/**
 * @author René Preuß <rene.p@own3d.tv>
 */
class ApiGuardTest extends TestCase
{

    public function testOwn3dIdGuard(): void
    {
        Own3dIdGuard::setRsaKeyLoader(fn() => file_get_contents(__DIR__ . '/../oauth-public.key'));

        $guard = new Own3dIdGuard(Auth::createUserProvider('users'));

        $request = new Request();
        $request->headers->set('Authorization', 'OAuth eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5MGE5NTFkMS1lYTUwLTRmZGEtOGM0ZC0yNzViODFmN2QyMTkiLCJqdGkiOiJmZGM4MmQwYjMzY2UyYzQ2ZjVhNzEyMDZkZGRiMTNlN2Y2YTAxNDRhNTZjOGU1MmRiOGM1MmE2ODc4NDRlM2QyYWVhY2VhMGNhNjdjOWIwYiIsImlhdCI6MTU5NDkwODIxNiwibmJmIjoxNTk0OTA4MjE2LCJleHAiOjE1OTYyMDQyMTYsInN1YiI6IjEiLCJzY29wZXMiOlsidXNlcjpyZWFkIiwiY29ubmVjdGlvbnMiXX0.hVV9_8hiDbk_hExJvByzVPubgJSb1ckc0ODXxQ00aUUcRPBmeTf1LAdaGbiRanv3GOju0KuZh8ucwxSntWm7Y4Wv_TUz7CLUKHFqxqVOy_fNfLS0n7SZu1fIzkfJatNzRUqlbQGjsOrfBaMZhVEyAC2fKrAZUCTQqv706p048jB7sF_-88qFL4_6BLI_N1AtOSVhrP_eXTAfnCDD4jEul8DqSnjJn1wB2LdP2vfBY7f8rJRN1LudOD8FNKFZJrHbWJhaqYKboGN_1ydebh47SVZA9JiGuuELciMtnMGCDxalmKROJRgPvrwgxMhqErmKEYQxqu985yREvwJ9KT6LVQHWXh17jioyPshh6oeQRC1gJrgdq2U3SNw50dvRIK--awU3T8jD61lfbjddIxcNmVl4x1nDfWE7z0lS7V6ilieQqCirXuUEygwpVoLqo1lzoU2ZuCs26i5lZDeT6zsFRjtAT6jniZHZ9DNJ85p0rsnbpdIPQrrVlzrBFMLqaVOdInlW5tQ3nL_emEkWzfNLuWPZH4eaWvCUFc25WydCGnbm-xctCYiPk7xeYUF1blCBDyo_FaqCjJWiQ48L-pzb-eXntALPiyscPS5qe5CjCtgpsdMHPEi6_W4cGdCk9aVE0npx4mfW8VbP7rf3H29DpItqKnFLG5lSGFQTAFQUuVI');

        $user = $guard->user($request);

        $this->assertNotNull($user);
        $this->assertEquals('1', $user->getKey());
    }
}
