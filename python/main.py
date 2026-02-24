from fastapi import FastAPI

app = FastAPI()

@app.get("/")
def read_root():
    return {"message": "Python Heavy Lifting API is Online"}

@app.get("/generate-guide/{topic}")
def generate_guide(topic: str):
    #this is where the heving lifiting logic will go
    return {"status": "success", "guide": f"study guide for {topic} is being processed!"}