# Gunakan image Python yang ringan
FROM python:3.10-slim

# Install poppler-utils (diperlukan marker-pdf)
RUN apt-get update && apt-get install -y \
    poppler-utils \
    git \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set folder kerja di dalam container
WORKDIR /app

# Salin file requirements ke container
COPY docker/converter-file/requirements.txt .

# Install dependensi Python (marker-pdf dan torch)
RUN pip install --no-cache-dir -r requirements.txt

# Volume mount untuk input/output folder
VOLUME ["/app/input", "/app/output"]

# Default command (marker_single bisa langsung dipanggil)
ENTRYPOINT ["marker_single"]
