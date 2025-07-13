import random

def submit_to_turnitin(file_path, filename):
    # Mô phỏng gọi API Turnitin
    similarity = round(random.uniform(10, 70), 2)
    print(f"[Turnitin] Giả lập gửi file '{filename}', similarity = {similarity}%")

    return {
        "status": "done",
        "similarity_score": similarity,
        "message": "Giả lập kiểm tra đạo văn thành công"
    }
