import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ReservasService {

  constructor(private http:HttpClient) { }

  private apiUrl = 'http://localhost:8000/';
  getAllReservas(queryParams:any){

    const url = this.apiUrl + 'reservas';

    return this.http.get(url,{params:queryParams});
  }

  getUsuarios(){
    const url = this.apiUrl +'usuarios';

    return this.http.get(url);
  }


  getLaboratorios(): Observable<any[]> {
    const url = this.apiUrl +'laboratorios'
    return this.http.get<any[]>(url);
  }

  postReservas(data:any){
    const url = this.apiUrl + 'reservas';

    return this.http.post(url,data);
  }

  getByIdReserva(id:string){
    const url = this.apiUrl + 'reservas/'+id;

    return this.http.get(url);
  }

  putReserva(id:string,data:any){
    const url = this.apiUrl + 'reservas/'+id;

    return this.http.put(url,data);
  }

  deleteReserva(id:string){
    const url = this.apiUrl + 'reservas/'+id;

    return this.http.delete(url);
  }

}
