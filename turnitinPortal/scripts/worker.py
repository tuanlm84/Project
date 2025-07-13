import redis
import json
import time
import os
import random
from turnitin_client import submit_to_turnitin

# ✅ Khởi tạo kết nối Redis
r = redis.Redis(host='127.0.0.1', port=6379, db=0)

print("[Worker] Đã kết nối Redis. Đang lắng nghe hàng đợi...")

while True:
    try:
        _, data = r.blpop('turnitin_jobs')  # Blocking chờ job
        job = json.loads(data)

        print(f"[Worker] Nhận job: {job['id']} - {job['filename']}")

        result = submit_to_turnitin(
            file_path=job['file_path'],
            filename=job['filename']
        )

        # Lưu kết quả vào thư mục public/results/
        result_path = f"/var/www/html/results/{job['id']}.json"
        with open(result_path, "w") as f:
            json.dump(result, f)

        print(f"[Worker] Xử lý xong job {job['id']} ✅")

    except Exception as e:
        print(f"[Worker] Lỗi: {e}")
        time.sleep(2)
