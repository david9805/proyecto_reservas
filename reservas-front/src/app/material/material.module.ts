import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {MatTableModule} from '@angular/material/table';
import {MatPaginatorModule} from '@angular/material/paginator';
import {MatMenuModule} from '@angular/material/menu';
import {MatIconModule} from '@angular/material/icon';
import {MatSnackBarModule} from '@angular/material/snack-bar';





@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    MatTableModule,
    MatPaginatorModule,
    MatMenuModule,
    MatIconModule,
    MatSnackBarModule

  ],
  exports:[
    MatTableModule,
    MatPaginatorModule,
    MatMenuModule,
    MatIconModule,
    MatSnackBarModule
  ]
})
export class MaterialModule { }
