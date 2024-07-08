import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MaterialModule } from '../material/material.module';
import { ReservasComponent } from './reservas.component';
import { ReactiveFormsModule } from '@angular/forms';
import { RouterModule, Routes } from '@angular/router';

const routes:Routes = [
  {
    path:'',
    component:ReservasComponent
  },
  {
    path:'create',
    loadChildren:()=>import('./create-reservas/create-reservas.module').then(m=>m.CreateReservasModule)
  },
  {
    path:'update/:id',
    loadChildren:()=>import('./create-reservas/create-reservas.module').then(m=>m.CreateReservasModule)
  }
]

@NgModule({
  declarations: [
    ReservasComponent
  ],
  imports: [
    CommonModule,
    MaterialModule,
    ReactiveFormsModule,
    RouterModule.forChild(routes)
  ]
})
export class ReservasModule { }
