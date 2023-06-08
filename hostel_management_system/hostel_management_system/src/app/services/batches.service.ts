import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class BatchesService {

  constructor(private httpclient:HttpClient) { }

  createBatch(data:any,headers:any){
    return this.httpclient.post('http://localhost:8000/api/createBatch',data,headers);
  }
  getBatches(data:any,headers:any){
    return this.httpclient.post('http://localhost:8000/api/Batches',data,headers);
  }
  getBatchesAll(headers:any){
    return this.httpclient.get('http://localhost:8000/api/Batchesall',headers);
  }
  updateBatch(data:any,headers:any){
    return this.httpclient.post('http://localhost:8000/api/updateBatch',data,headers);
  }
  deletebatch(data:any,headers:any){
    return this.httpclient.post('http://localhost:8000/api/deleteBatch',data,headers);
  }
  activateBatch(data:any,headers:any){
    return this.httpclient.post('http://localhost:8000/api/activateBatch',data,headers);
  }
  deactivate(data:any,headers:any){
    return this.httpclient.post('http://localhost:8000/api/deactivateBatch',data,headers);
  }
}
