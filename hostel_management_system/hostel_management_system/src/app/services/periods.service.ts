import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PeriodsService {

  constructor(private http:HttpClient) { }

  getperiods(headers:any){
   return this.http.get('http://localhost:8000/api/getperiods',headers);
  }
}
