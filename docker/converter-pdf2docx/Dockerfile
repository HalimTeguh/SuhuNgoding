FROM python:3.11-slim

WORKDIR /app

# Copy script ke dalam container
COPY entrypoint.sh /app/entrypoint.sh
COPY convert.py /app/convert.py

# Berikan permission execute
RUN chmod +x /app/entrypoint.sh

# Install libreoffice (optional, belum dipakai sekarang) dan pdf2docx
RUN apt-get update && apt-get install -y \
    libreoffice \
    poppler-utils \
    && pip install pdf2docx python-docx

# Buat folder /app/share untuk volume
RUN mkdir -p /app/share

ENTRYPOINT ["/app/entrypoint.sh"]
