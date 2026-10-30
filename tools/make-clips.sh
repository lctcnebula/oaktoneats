#!/bin/bash
# Generates the coffee, breakfast and refreshers clips with OpenAI's video API (Sora),
# saving each as assets/video/clip-N.mp4 with a clip-N.jpg poster.
# Needs OPENAI_API_KEY in the environment. Paid per second of video. Run from the project folder:
#   OPENAI_API_KEY=... bash tools/make-clips.sh
set -euo pipefail
: "${OPENAI_API_KEY:?Set OPENAI_API_KEY first}"
cd "$(dirname "$0")/.."

STYLE="Cinematic close-up food video, dark moody cafe, warm gold light, black background, shallow depth of field, slow motion, no people's faces, no text, no logos."
CLIPS=(
  "7|An iced Spanish latte: espresso slowly poured over milk and ice in a clear glass, swirling into caramel tones. $STYLE"
  "8|A barista's hands placing a warm egg and cheese croissant sandwich on a plate, cheese melting, steam rising. No meat. $STYLE"
  "9|Fresh lemonade with mint being poured over ice in a tall glass, lemon slices, condensation on the glass. $STYLE"
)

for c in "${CLIPS[@]}"; do
  n="${c%%|*}"; prompt="${c#*|}"
  echo "clip-$n: starting"
  id=$(curl -s https://api.openai.com/v1/videos -H "Authorization: Bearer $OPENAI_API_KEY" \
    -F model=sora-2 -F seconds=8 -F size=1280x720 -F prompt="$prompt" | python3 -c 'import sys,json; d=json.load(sys.stdin); print(d.get("id") or sys.exit(json.dumps(d)))')
  while :; do
    status=$(curl -s "https://api.openai.com/v1/videos/$id" -H "Authorization: Bearer $OPENAI_API_KEY" | python3 -c 'import sys,json; print(json.load(sys.stdin).get("status"))')
    echo "clip-$n: $status"
    [ "$status" = completed ] && break
    [ "$status" = failed ] && { echo "clip-$n failed"; continue 2; }
    sleep 10
  done
  curl -s -L "https://api.openai.com/v1/videos/$id/content" -H "Authorization: Bearer $OPENAI_API_KEY" -o "assets/video/clip-$n.raw.mp4"
  ffmpeg -y -loglevel error -i "assets/video/clip-$n.raw.mp4" -an -c:v libx264 -crf 24 -preset slow -movflags +faststart "assets/video/clip-$n.mp4"
  ffmpeg -y -loglevel error -ss 2 -i "assets/video/clip-$n.mp4" -frames:v 1 -q:v 3 "assets/video/clip-$n.jpg"
  rm "assets/video/clip-$n.raw.mp4"
  echo "clip-$n: saved"
done
