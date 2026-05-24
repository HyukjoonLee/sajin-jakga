# 사진작가 (Sajin-Jakga)
> **작가를 위한 가장 완벽한 공간**

현직 프로 작가부터 아마추어, 모델 지망생까지 사진을 사랑하는 모든 이들이 모여 소통하고 성장하는 열린 커뮤니티 서비스입니다.

## 🚀 프로젝트 개요
이 프로젝트는 사진 업계 종사자 및 동호인들을 위한 올인원 플랫폼을 지향합니다.
- **구인/구직**: 작가와 모델, 스태프 간의 매칭 서비스
- **장소 대여**: 촬영 스튜디오 및 로케이션 정보 공유 및 예약
- **중고 거래**: 카메라 바디, 렌즈 등 전문 촬영 장비 마켓
- **커뮤니티**: 작품 전시 및 촬영 노하우 공유

## 🎯 프로젝트 목적
이 저장소는 사진 커뮤니티 서비스를 구상하면서, 라이믹스를 Docker 기반으로 구성해보는 학습과 실험을 함께 진행하기 위해 만들었습니다.

- 로컬 환경에 Nginx, MySQL, Redis를 직접 설치하지 않고 Docker Compose로 실행합니다.
- Rhymix, PHP-FPM, Nginx, MySQL, Redis가 함께 동작하는 구조를 연습합니다.
- `.env` 기반 설정 주입과 `files/config/config.php` 자동 생성을 실험합니다.
- GitHub 이슈/PR 템플릿과 CI를 포함한 저장소 운영 방식을 정리합니다.
- Codex와 Gemini를 활용한 코드 리뷰 및 개발 보조 워크플로우를 문서화합니다.

## 🛠 기술 스택
- **Engine**: [Rhymix](https://rhymix.org/) 2.1.20 (PHP 기반 CMS)
- **Environment**: Docker & Docker Compose
- **Backend**: PHP 8.2-fpm
- **Database**: MySQL 8.0
- **Cache**: Redis 7.0
- **Web Server**: Nginx 1.25

## 💻 시작하기 (개발 환경 구축)

이 프로젝트는 Docker Compose로 재현 가능한 로컬 개발 환경을 구성합니다.

### 1. 환경 변수 설정
`.env.example` 파일을 복사하여 `.env` 파일을 생성하고 필요한 정보를 수정합니다.
```bash
cp .env.example .env
```

### 2. 컨테이너 실행
```bash
docker-compose up -d --build
```
*컨테이너 실행 시 `files/config/config.php` 파일이 없으면 자동으로 생성됩니다.*

### 3. 접속 정보
- **Web**: [http://localhost:8080](http://localhost:8080)
- **Admin**: [http://localhost:8080/admin](http://localhost:8080/admin)
- **Database (Local Tool)**: `127.0.0.1:3307` (계정 정보는 `.env` 파일의 설정을 따릅니다.)

## ⚙️ 설정 변경 및 업데이트
- `.env` 파일에서 DB 정보나 URL 등을 변경한 경우, 기존의 설정 파일을 삭제해야 변경 사항이 반영됩니다.
  *(주의: 설정 파일 삭제 시 세션 및 암호화 키가 초기화될 수 있으므로 초기 개발 단계에서만 실행하세요.)*
  ```bash
  rm files/config/config.php
  docker-compose restart app
  ```

## 🔐 Git 관리 기준
- Git에 올리는 파일: `Dockerfile`, `docker-compose.yml`, `entrypoint.sh`, `nginx/nginx.conf`, `php.ini`, `.env.example`, `.dockerignore`, `scripts/setup-config.php`, `AGENTS.md`, `GEMINI.md`
- Git에 올리지 않는 파일: `.env`, `files/`, `files/config/config.php`
- `files/config/config.php`는 컨테이너 시작 시 `.env` 값을 바탕으로 자동 생성되며, DB 비밀번호와 암호화 키를 포함하므로 저장소에 커밋하면 안 됩니다.

## 🧪 GitHub 및 자동 검증
- Pull Request 템플릿: 작업 내용, 관련 이슈, 테스트 체크리스트를 기록합니다.
- Issue 템플릿: 버그 리포트와 기능 제안을 분리합니다.
- GitHub Actions CI:
  - PHP 8.2 환경에서 PHP 문법 검사를 실행합니다.
  - Docker Compose 설정을 검증합니다.
  - Docker 이미지 빌드를 검증합니다.

## 🤖 AI 작업 지침
- `AGENTS.md`: Codex가 작업 및 리뷰 시 따르는 공통 지침입니다.
- `GEMINI.md`: Gemini가 참고하는 전용 지침이며, 정책의 원본은 `AGENTS.md`입니다.
- 두 문서는 보안, Git 관리, Docker 검증, 리뷰 우선순위를 일관되게 유지하기 위한 용도입니다.

## ⚠️ 주의 사항
- 현재 프로젝트는 **초기 개발 및 Docker 기반 라이믹스 구성 연습 단계**입니다.
- 보안을 위해 `.env` 파일은 절대 Git에 커밋하지 마세요.
- DB 및 Redis 포트는 로컬(127.0.0.1) 접속만 허용되어 있습니다.

## ⚖️ 라이선스
이 프로젝트는 Rhymix 엔진을 기반으로 하며, [GNU GPL v2](http://korea.gnu.org/documents/copyleft/gpl.ko.html) 라이선스를 따릅니다.
