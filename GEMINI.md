# Gemini Instructions for Sajin-Jakga Project

**중요:** 이 프로젝트의 공통 보안 및 운영 정책의 원본(Source of Truth)은 `AGENTS.md` 파일입니다. Gemini는 작업 및 코드 리뷰 시 반드시 이 문서와 `AGENTS.md`의 정책을 일관되게 적용해야 하며 정책적 충돌(Drift)이 발생하지 않도록 해야 합니다.

## 1. 프로젝트 개요
- **프로젝트명**: 사진작가 (Sajin-Jakga)
- **서비스 목적**: 프로/아마추어 사진작가, 모델 지망생을 위한 구인/구직, 장소 대여, 중고 거래 커뮤니티 플랫폼
- **기술 스택**: Rhymix 2.1.20, PHP 8.2-fpm, Nginx 1.25, MySQL 8.0, Redis 7.0 (Docker 기반 환경)

## 2. Docker 기반 자동 설정 (Auto-Configuration)
- 이 프로젝트는 `.env` 파일 생성 후 Docker Compose 실행 시 자동으로 환경이 구축되도록 설계되어 있습니다.
- 컨테이너 시작 시 `entrypoint.sh`가 `scripts/setup-config.php`를 실행하여 `.env` 값을 바탕으로 `files/config/config.php`를 동적으로 생성합니다.
- 캐시 드라이버는 Redis를 사용하며, 서버 주소 형식은 라이믹스 규격에 맞춰 반드시 `redis://redis:6379` 형태를 유지해야 합니다.
- PHP 확장은 라이믹스 구동 및 런타임 안정성에 필수적인 `curl`, `mbstring`, `gd`, `zip`, `pdo_mysql`, `redis`를 모두 포함해야 합니다.

## 3. Git 관리 및 파일 정책 (Git Hygiene)
- **절대 커밋 금지 (DO NOT COMMIT)**:
  - `.env` (실제 보안 정보 포함)
  - `files/` 디렉토리 전체 (업로드 파일 및 캐시 데이터)
  - `files/config/config.php` (보안 키를 포함하며 런타임에 자동 생성됨)
- **반드시 커밋 (MUST COMMIT)**:
  - `.env.example` (개발용 예시값이 포함된 환경 변수 템플릿)
  - `scripts/setup-config.php`, `entrypoint.sh`, `Dockerfile`, `docker-compose.yml`
  - `nginx/nginx.conf`, `php.ini`, `.dockerignore`, `README.md`
  - `AGENTS.md`, `GEMINI.md`

## 3-1. GitHub 워크플로우 및 권한
- 기본 브랜치 흐름은 `develop -> main` 입니다.
- 이 프로젝트는 1인 프로젝트이므로 Pull Request는 필수가 아니며, 사용자가 PR 기반 리뷰 흐름을 명시적으로 원할 때만 사용합니다.
- `main` 브랜치는 실수로 직접 push하는 것을 막기 위해 GitHub Branch Protection Rule로 보호할 수 있습니다.
- 권장 보호 설정:
  - `main` 직접 push 제한
  - 보호된 업데이트를 사용할 경우 CI 상태 체크 통과 필수
  - 실수 방지를 위해 관리자/소유자 bypass도 제한
- Gemini는 저장소 상태나 원격 상태를 변경하는 작업 전 반드시 사용자에게 명시적 동의를 받아야 합니다.
- 명시적 동의가 필요한 작업:
  - `git add`, `git commit`, `git push`
  - 브랜치 생성, 삭제, 전환
  - Pull Request 생성
  - 브랜치 병합
  - 태그 생성 및 릴리스 작업
  - force push, history rewrite, branch protection 우회
- 사용자가 정확히 요청하지 않는 한 force push, 브랜치 삭제, 히스토리 재작성, 보호 규칙 우회는 수행하지 않습니다.
- 커밋 메시지는 가능한 한 한글로 작성합니다. 필요하면 `feat:`, `fix:`, `docs:`, `chore:` 같은 짧은 prefix는 사용할 수 있지만 제목 본문은 한글을 우선합니다.

## 4. 인프라 검증 및 보안 기준
- **Port Binding**: 외부 관리 툴용 포트(MySQL 3307, Redis 6380)는 반드시 `127.0.0.1`에 바인딩되어야 합니다.
- **Healthcheck**: MySQL과 Redis는 `healthcheck`가 필수이며, 앱 서비스는 DB와 Redis가 모두 준비된 후(`service_healthy`) 실행되어야 합니다.
- **Nginx Security**: `.`으로 시작하는 숨김 파일 및 `files/config`, `scripts`, `Dockerfile` 등 내부 설정 파일에 대한 웹 접근을 명시적으로 차단(`deny all`)해야 합니다.

## 5. 작업 및 리뷰 우선순위
1. **보안 (Security)**: 비밀번호 노출 방지, 웹 루트 경로 보호.
2. **데이터 안정성 (Data Integrity)**: 볼륨 마운트 및 영속성 보장.
3. **런타임 가용성 (Availability)**: DB 동기화, 필수 PHP 확장, 캐시 드라이버 형식.
4. **Git Hygiene**: 커밋 제외 대상 엄수.
5. **일관성 (Consistency)**: 문서와 실제 설정 간의 불일치 제거.

## 6. 에이전트 행동 지침 (Operational Guidelines)
- **컨텍스트 효율성**: 파일 전체 읽기보다는 `grep_search`와 `read_file`의 라인 범위 지정을 활용하여 정밀하게 작업할 것.
- **투명한 진행 공유**: 단계별 작업 시 `update_topic`을 호출하여 현재의 전략적 의도와 요약을 공유할 것.
- **전문 에이전트 활용**: 광범위한 조사 시 `invoke_agent`를 통해 `codebase_investigator` 등 서브 에이전트에게 위임할 것.
- **선제적 검증**: 수정한 설정은 사용자에게 확인을 요청하기 전 `run_shell_command`로 직접 유효성을 검증할 것.

## 7. 리뷰 출력 형식 (Review Format)
리뷰 결과는 아래 형식을 준수하여 명확하게 출력합니다:
- **심각도**: [High] / [Medium] / [Low]
- **위치**: 파일 경로 및 라인 번호
- **영향**: 해당 코드가 시스템이나 보안에 미치는 영향
- **권장 수정**: 구체적인 수정 코드 또는 가이드라인
