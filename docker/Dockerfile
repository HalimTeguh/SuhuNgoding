# Gunakan base image Python
FROM python:3.11-slim

# Buat direktori kerja
WORKDIR /app

# Salin entrypoint.sh ke dalam container
COPY entrypoint.sh /app/entrypoint.sh

# Beri izin eksekusi untuk script
RUN chmod +x /app/entrypoint.sh

# Atur entrypoint agar bisa menerima kode dari stdin
ENTRYPOINT ["/app/entrypoint.sh"]
